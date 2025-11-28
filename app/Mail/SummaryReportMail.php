<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SummaryReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $period,
        public readonly array $summary
    ) {}

    public function build(): self
    {
        $periodLabel = ucfirst($this->period);

        return $this->subject("Your {$periodLabel} Spendly summary")
            ->view('emails.summary-report')
            ->with([
                'period' => $this->period,
                'summary' => $this->summary,
            ]);
    }
}


