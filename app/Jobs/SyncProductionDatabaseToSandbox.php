<?php

namespace App\Jobs;

use App\Models\SystemToolRun;
use App\Services\SystemTools\DatabaseSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
}
