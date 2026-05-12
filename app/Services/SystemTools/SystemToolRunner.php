<?php

namespace App\Services\SystemTools;

use App\Models\SystemToolRun;
use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Throwable;

class SystemToolRunner
{
    /**
     * @return array<string, array{label: string, command: string, parameters: array<string, mixed>, destructive: bool}>
     */
    public function tools(): array
    {
        return [
            'optimize_clear' => ['label' => 'optimize:clear', 'command' => 'optimize:clear', 'parameters' => [], 'destructive' => false],
            'cache_clear' => ['label' => 'cache:clear', 'command' => 'cache:clear', 'parameters' => [], 'destructive' => false],
            'config_clear' => ['label' => 'config:clear', 'command' => 'config:clear', 'parameters' => [], 'destructive' => false],
            'route_clear' => ['label' => 'route:clear', 'command' => 'route:clear', 'parameters' => [], 'destructive' => false],
            'view_clear' => ['label' => 'view:clear', 'command' => 'view:clear', 'parameters' => [], 'destructive' => false],
            'storage_link' => ['label' => 'storage:link', 'command' => 'storage:link', 'parameters' => [], 'destructive' => false],
            'migrate_force' => ['label' => 'migrate --force', 'command' => 'migrate', 'parameters' => ['--force' => true], 'destructive' => true],
            'queue_restart' => ['label' => 'queue:restart', 'command' => 'queue:restart', 'parameters' => [], 'destructive' => false],
        ];
    }

    public function run(string $toolKey, ?int $userId = null): SystemToolRun
    {
        $tool = $this->tools()[$toolKey] ?? null;

        if (! $tool) {
            throw new InvalidArgumentException("Unsupported system tool [{$toolKey}].");
        }

        $run = SystemToolRun::query()->create([
            'user_id' => $userId,
            'tool' => 'laravel',
            'action' => $tool['label'],
            'status' => 'running',
            'started_at' => now(),
        ]);

        $startedAt = hrtime(true);

        try {
            $exitCode = Artisan::call($tool['command'], $tool['parameters']);

            $run->update([
                'status' => $exitCode === 0 ? 'succeeded' : 'failed',
                'exit_code' => $exitCode,
                'output' => $this->truncateOutput(Artisan::output()),
                'duration_ms' => $this->durationInMilliseconds($startedAt),
                'finished_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $run->update([
                'status' => 'failed',
                'error' => $this->truncateOutput($exception->getMessage()),
                'duration_ms' => $this->durationInMilliseconds($startedAt),
                'finished_at' => now(),
            ]);
        }

        return $run->refresh();
    }

    private function durationInMilliseconds(int $startedAt): int
    {
        return (int) round((hrtime(true) - $startedAt) / 1_000_000);
    }

    private function truncateOutput(?string $output): ?string
    {
        if (blank($output)) {
            return null;
        }

        return mb_substr($output, 0, 10000);
    }
}
