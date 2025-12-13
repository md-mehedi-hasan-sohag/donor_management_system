<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire campaigns that have reached their end date and archive old completed campaigns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting campaign expiry and archiving process...');

        // Step 1: Expire active campaigns that have passed their end date
        $expiredCount = $this->expireCampaigns();

        // Step 2: Archive campaigns that have been completed for more than their archive period
        $archivedCount = $this->archiveCampaigns();

        $this->info("✓ Expired {$expiredCount} campaign(s)");
        $this->info("✓ Archived {$archivedCount} campaign(s)");
        $this->info('Campaign expiry process completed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Expire campaigns that have passed their end date
     */
    protected function expireCampaigns(): int
    {
        $campaigns = Campaign::where('status', 'active')
            ->where('end_date', '<', now())
            ->whereNull('completed_at')
            ->get();

        $count = 0;

        foreach ($campaigns as $campaign) {
            DB::transaction(function () use ($campaign) {
                $campaign->update([
                    'status' => 'expired',
                    'completed_at' => now(),
                ]);

                // Create a final progress update for the campaign
                $campaign->updates()->create([
                    'update_type' => 'progress',
                    'title' => 'Campaign Ended',
                    'content' => sprintf(
                        'This campaign has ended. Final amount raised: $%s (%.1f%% of goal). Thank you to all %d donors who supported this cause!',
                        number_format($campaign->current_amount, 2),
                        $campaign->progressPercentage(),
                        $campaign->total_donors
                    ),
                ]);
            });

            $count++;
            $this->line("  - Expired: {$campaign->title}");
        }

        return $count;
    }

    /**
     * Archive campaigns that have been expired for a specified period
     */
    protected function archiveCampaigns(): int
    {
        $campaigns = Campaign::where('status', 'expired')
            ->whereNotNull('completed_at')
            ->whereNull('archived_at')
            ->get()
            ->filter(function ($campaign) {
                $archiveDate = $campaign->completed_at->addDays($campaign->days_until_archive);
                return now()->greaterThanOrEqualTo($archiveDate);
            });

        $count = 0;

        foreach ($campaigns as $campaign) {
            $campaign->update([
                'status' => 'archived',
                'archived_at' => now(),
            ]);

            $count++;
            $this->line("  - Archived: {$campaign->title}");
        }

        return $count;
    }
}
