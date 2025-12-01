<?php

namespace App\Mail;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SummaryReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $period,
        public readonly array $summary,
        public readonly User $user
    ) {}

    public function build(): self
    {
        $periodLabel = ucfirst($this->period);
        
        // Generate PDF
        $pdf = Pdf::loadView('pdf.statement', [
            'period' => $this->period,
            'summary' => $this->summary,
            'user' => $this->user,
        ])->setPaper('a4', 'portrait');
        
        $fileName = 'Spendly_' . $periodLabel . '_Statement_' . now()->format('Y-m-d') . '.pdf';

        return $this->subject("Your {$periodLabel} Spendly Statement")
            ->view('emails.summary-report')
            ->with([
                'period' => $this->period,
                'summary' => $this->summary,
                'user' => $this->user,
            ])
            ->attachData($pdf->output(), $fileName, [
                'mime' => 'application/pdf',
            ]);
    }
}




