<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class SummaryReportService
{
    /**
     * Build income/expense summary data for the given period.
     */
    public function build(User $user, string $period): array
    {
        [$start, $end, $label] = $this->resolvePeriodRange($period);

        $baseQuery = Transaction::forUser($user->id)
            ->whereBetween('transactions.transaction_date', [$start, $end]);

        $incomeTotal = (clone $baseQuery)->income()->sum('transactions.amount');
        $expenseTotal = (clone $baseQuery)->expense()->sum('transactions.amount');
        $transactionCount = (clone $baseQuery)->count();

        $topCategories = (clone $baseQuery)
            ->expense()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(transactions.amount) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                return [
                    'category' => $row->category,
                    'total' => (float) $row->total,
                ];
            });

        return [
            'period' => $period,
            'period_label' => $label,
            'start' => $start,
            'end' => $end,
            'total_income' => (float) $incomeTotal,
            'total_expense' => (float) $expenseTotal,
            'net' => (float) ($incomeTotal - $expenseTotal),
            'transactions_count' => $transactionCount,
            'top_categories' => $topCategories,
        ];
    }

    protected function resolvePeriodRange(string $period): array
    {
        $now = Carbon::now(config('app.timezone'));

        return match ($period) {
            'daily' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
                'Today · ' . $now->format('M d, Y'),
            ],
            'weekly' => [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
                'This Week · ' . $now->copy()->startOfWeek()->format('M d') . ' - ' . $now->copy()->endOfWeek()->format('M d, Y'),
            ],
            'monthly' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
                $now->format('F Y'),
            ],
            default => throw new \InvalidArgumentException('Invalid period supplied.'),
        };
    }
}


