<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ApiLog;
use Illuminate\Console\Command;

class DeleteOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete logs that are older that 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Delete logs older than 30 days
        $deletedLogsCount = ApiLog::where('cached_at', '<', $thirtyDaysAgo)->delete();

        $this->info("Deleted {$deletedLogsCount} logs that were older than 30 days.");
    }
}
