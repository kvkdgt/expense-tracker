<?php

namespace App\Console\Commands;

use App\Mail\SummaryReportMail;
use App\Models\User;
use App\Services\SummaryReportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendSummaryEmails extends Command
{
    protected $signature = 'summary:send {period : daily|weekly|monthly}';

    protected $description = 'Send Spendly income & expense summary emails to users';

    public function handle(SummaryReportService $service): int
    {
        $period = $this->argument('period');

        if (! in_array($period, ['daily', 'weekly', 'monthly'], true)) {
            $this->error('Invalid period. Use daily, weekly, or monthly.');

            return Command::FAILURE;
        }

        User::chunk(100, function ($users) use ($service, $period) {
            foreach ($users as $user) {
                $summary = $service->build($user, $period);

                if ($summary['transactions_count'] === 0) {
                    continue;
                }

                Mail::to($user->email)->queue(new SummaryReportMail($period, $summary));
            }
        });

        $this->info("{$period} summary emails queued successfully.");

        return Command::SUCCESS;
    }
}


