<?php

namespace App\Jobs;

use App\Models\SystemToolRun;
use App\Services\SystemTools\DatabaseSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SyncProductionDatabaseToSandbox implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1200;

    public function __construct(public int $systemToolRunId) {}

    public function handle(DatabaseSyncService $databaseSyncService): void
    {
        $run = SystemToolRun::query()->findOrFail($this->systemToolRunId);

        $databaseSyncService->run($run);
    }

    public function failed(Throwable $exception): void
    {
        SystemToolRun::query()
            ->whereKey($this->systemToolRunId)
            ->whereIn('status', ['pending', 'running'])
            ->update([
                'status' => 'failed',
                'error' => mb_substr($exception->getMessage(), 0, 10000),
                'finished_at' => now(),
            ]);
    }
}
