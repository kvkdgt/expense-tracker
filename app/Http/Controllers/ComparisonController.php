<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComparisonController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $month1 = $request->input('month1', $lastMonth->format('Y-m'));
        $month2 = $request->input('month2', $currentMonth->format('Y-m'));

        return view('comparison.index', compact('month1', 'month2'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'month1' => ['required', 'date_format:Y-m'],
            'month2' => ['required', 'date_format:Y-m'],
        ]);

        $user = Auth::user();
        $month1 = Carbon::createFromFormat('Y-m', $request->month1);
        $month2 = Carbon::createFromFormat('Y-m', $request->month2);

        // Get totals for both months
        $month1Totals = $this->getMonthTotals($user->id, $month1);
        $month2Totals = $this->getMonthTotals($user->id, $month2);

        // Get category-wise comparison
        $categoryComparison = $this->getCategoryComparison($user->id, $month1, $month2);

        // Calculate savings
        $month1Savings = $month1Totals['income'] - $month1Totals['expense'];
        $month2Savings = $month2Totals['income'] - $month2Totals['expense'];

        return response()->json([
            'month1' => [
                'label' => $month1->format('F Y'),
                'income' => round($month1Totals['income'], 2),
                'expense' => round($month1Totals['expense'], 2),
                'savings' => round($month1Savings, 2),
            ],
            'month2' => [
                'label' => $month2->format('F Y'),
                'income' => round($month2Totals['income'], 2),
                'expense' => round($month2Totals['expense'], 2),
                'savings' => round($month2Savings, 2),
            ],
            'differences' => [
                'income' => round($month2Totals['income'] - $month1Totals['income'], 2),
                'expense' => round($month2Totals['expense'] - $month1Totals['expense'], 2),
                'savings' => round($month2Savings - $month1Savings, 2),
            ],
            'categories' => $categoryComparison,
        ]);
    }

    private function getMonthTotals($userId, Carbon $month)
    {
        $income = Transaction::forUser($userId)
            ->forMonth($month->year, $month->month)
            ->income()
            ->sum('amount');

        $expense = Transaction::forUser($userId)
            ->forMonth($month->year, $month->month)
            ->expense()
            ->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
        ];
    }

    private function getCategoryComparison($userId, Carbon $month1, Carbon $month2)
    {
        // Get all categories with their spending in both months
        $month1Categories = Transaction::forUser($userId)
            ->forMonth($month1->year, $month1->month)
            ->expense()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get()
            ->keyBy('name');

        $month2Categories = Transaction::forUser($userId)
            ->forMonth($month2->year, $month2->month)
            ->expense()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get()
            ->keyBy('name');

        // Merge categories from both months
        $allCategories = $month1Categories->keys()->merge($month2Categories->keys())->unique();

        $comparison = [];
        foreach ($allCategories as $categoryName) {
            $month1Amount = $month1Categories->get($categoryName)?->total ?? 0;
            $month2Amount = $month2Categories->get($categoryName)?->total ?? 0;
            $difference = $month2Amount - $month1Amount;

            $comparison[] = [
                'name' => $categoryName,
                'month1' => round($month1Amount, 2),
                'month2' => round($month2Amount, 2),
                'difference' => round($difference, 2),
                'trend' => $difference > 0 ? 'up' : ($difference < 0 ? 'down' : 'same'),
                'message' => $this->generateComparisonMessage($categoryName, $month1Amount, $month2Amount, $month1, $month2),
            ];
        }

        // Sort by absolute difference
        usort($comparison, function ($a, $b) {
            return abs($b['difference']) <=> abs($a['difference']);
        });

        return $comparison;
    }

    private function generateComparisonMessage($category, $month1Amount, $month2Amount, Carbon $month1, Carbon $month2)
    {
        $difference = abs($month2Amount - $month1Amount);
        $month1Label = $month1->format('M');
        $month2Label = $month2->format('M');

        if ($month2Amount > $month1Amount) {
            return sprintf(
                'You spent ₹%.0f more on %s in %s compared to %s',
                $difference,
                $category,
                $month2Label,
                $month1Label
            );
        } elseif ($month2Amount < $month1Amount) {
            return sprintf(
                'You spent ₹%.0f less on %s in %s compared to %s',
                $difference,
                $category,
                $month2Label,
                $month1Label
            );
        } else {
            return sprintf('Same spending on %s in both months', $category);
        }
    }
}



