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

        $sentCount = 0;
        $skippedCount = 0;

        User::chunk(100, function ($users) use ($service, $period, &$sentCount, &$skippedCount) {
            foreach ($users as $user) {
                try {
                    $summary = $service->build($user, $period);

                    if ($summary['transactions_count'] === 0) {
                        $skippedCount++;
                        continue;
                    }

                    Mail::to($user->email)->send(new SummaryReportMail($period, $summary, $user));
                    $sentCount++;
                } catch (\Exception $e) {
                    $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
                }
            }
        });

        $this->info("{$period} summary emails sent: {$sentCount}, skipped: {$skippedCount}.");

        return Command::SUCCESS;
    }
}




