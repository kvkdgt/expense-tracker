<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Current month stats
        $currentMonthIncome = Transaction::forUser($user->id)
            ->forMonth($currentMonth->year, $currentMonth->month)
            ->income()
            ->sum('amount');

        $currentMonthExpense = Transaction::forUser($user->id)
            ->forMonth($currentMonth->year, $currentMonth->month)
            ->expense()
            ->sum('amount');

        // Last month stats
        $lastMonthIncome = Transaction::forUser($user->id)
            ->forMonth($lastMonth->year, $lastMonth->month)
            ->income()
            ->sum('amount');

        $lastMonthExpense = Transaction::forUser($user->id)
            ->forMonth($lastMonth->year, $lastMonth->month)
            ->expense()
            ->sum('amount');

        // Daily spending for chart (last 30 days)
        $dailyData = Transaction::forUser($user->id)
            ->where('transaction_date', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Category-wise expense for current month
        $categoryExpenses = Transaction::forUser($user->id)
            ->forMonth($currentMonth->year, $currentMonth->month)
            ->expense()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::forUser($user->id)
            ->with('category')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'currentMonthIncome',
            'currentMonthExpense',
            'lastMonthIncome',
            'lastMonthExpense',
            'dailyData',
            'categoryExpenses',
            'recentTransactions',
            'currentMonth'
        ));
    }

    public function getChartData(Request $request)
    {
        $user = Auth::user();
        $days = $request->input('days', 30);

        $data = Transaction::forUser($user->id)
            ->where('transaction_date', '>=', Carbon::now()->subDays($days))
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }
}

