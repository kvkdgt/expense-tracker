<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $type = $request->input('type', 'expense');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Set default dates based on period
        $dates = $this->getDateRange($period, $startDate, $endDate);
        
        return view('reports.index', [
            'period' => $period,
            'type' => $type,
            'startDate' => $dates['start']->format('Y-m-d'),
            'endDate' => $dates['end']->format('Y-m-d'),
        ]);
    }

    public function getData(Request $request)
    {
        $request->validate([
            'period' => ['required', 'in:daily,weekly,monthly,custom'],
            'type' => ['required', 'in:income,expense'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $user = Auth::user();
        $type = $request->input('type');
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        // Get category-wise totals
        $categoryData = Transaction::forUser($user->id)
            ->where('transactions.type', $type)
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name as category',
                DB::raw('SUM(transactions.amount) as total'),
                DB::raw('COUNT(transactions.id) as count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        // Calculate total
        $total = $categoryData->sum('total');

        // Format data with percentages
        $formattedData = $categoryData->map(function ($item) use ($total) {
            return [
                'category' => $item->category,
                'total' => round($item->total, 2),
                'count' => $item->count,
                'percentage' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0,
            ];
        });

        // Get period label
        $periodLabel = $this->getPeriodLabel($request->input('period'), $startDate, $endDate);

        return response()->json([
            'categories' => $formattedData,
            'total' => round($total, 2),
            'period_label' => $periodLabel,
            'start_date' => $startDate->format('d M Y'),
            'end_date' => $endDate->format('d M Y'),
            'type' => $type,
        ]);
    }

    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        if ($period === 'custom' && $startDate && $endDate) {
            return [
                'start' => Carbon::parse($startDate),
                'end' => Carbon::parse($endDate),
            ];
        }

        $end = Carbon::today();
        
        switch ($period) {
            case 'daily':
                $start = Carbon::yesterday();
                break;
            case 'weekly':
                $start = Carbon::now()->subWeek()->startOfWeek();
                $end = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'monthly':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end = Carbon::now()->subMonth()->endOfMonth();
                break;
            default:
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end = Carbon::now()->subMonth()->endOfMonth();
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    private function getPeriodLabel($period, $startDate, $endDate)
    {
        switch ($period) {
            case 'daily':
                return $startDate->format('d M Y');
            case 'weekly':
                return $startDate->format('d M') . ' - ' . $endDate->format('d M Y');
            case 'monthly':
                return $startDate->format('F Y');
            case 'custom':
                return $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
            default:
                return $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        }
    }
}

