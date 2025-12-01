<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $transactionCount = Transaction::count();
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');

        return view('admin.dashboard', compact(
            'userCount',
            'transactionCount',
            'totalIncome',
            'totalExpense'
        ));
    }
}



