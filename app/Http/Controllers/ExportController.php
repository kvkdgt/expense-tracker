<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\SummaryReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    protected $summaryService;

    public function __construct(SummaryReportService $summaryService)
    {
        $this->summaryService = $summaryService;
    }

    public function exportTransactions(Request $request)
    {
        $user = Auth::user();
        
        $transactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'spendly_transactions_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Date', 'Type', 'Category', 'Amount', 'Note']);
            
            // Add transaction data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_date->format('Y-m-d'),
                    ucfirst($transaction->type),
                    $transaction->category->name,
                    $transaction->amount,
                    $transaction->note ?? '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadStatement(Request $request, string $period)
    {
        if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
            return redirect()->route('settings.index')->with('error', 'Invalid period specified.');
        }

        $user = Auth::user();
        $summary = $this->summaryService->build($user, $period);

        $pdf = Pdf::loadView('pdf.statement', [
            'user' => $user,
            'summary' => $summary,
            'period' => $period,
        ]);

        $filename = 'Spendly_' . ucfirst($period) . '_Statement_' . now()->format('M_d_Y') . '.pdf';

        return $pdf->download($filename);
    }
}




