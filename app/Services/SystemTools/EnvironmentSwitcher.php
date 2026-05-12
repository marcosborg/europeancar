<?php

namespace App\Services\SystemTools;

use App\Models\SystemToolRun;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class EnvironmentSwitcher
{
    /**
     * @return array<string, string>
     */
    public function environments(): array
    {
        return [
            'production' => base_path('.env.production'),
            'sandbox' => base_path('.env.local'),
        ];
    }

    public function currentLabel(): string
    {
        return app()->isProduction() ? 'PRODUCTION' : 'SANDBOX';
    }

    public function switchTo(string $environment, ?int $userId = null): SystemToolRun
    {
        $source = $this->environments()[$environment] ?? null;

        if (! $source) {
            throw new InvalidArgumentException("Unsupported environment [{$environment}].");
        }

        $run = SystemToolRun::query()->create([
            'user_id' => $userId,
            'tool' => 'environment',
            'action' => "switch:{$environment}",
            'status' => 'running',
            'started_at' => now(),
        ]);

        $startedAt = hrtime(true);

        try {
            if (! File::exists($source)) {
                throw new RuntimeException("Environment file does not exist: {$source}");
            }

            $target = base_path('.env');

            if (File::exists($target)) {
                File::copy($target, base_path('.env.backup'));
            }

            File::copy($source, $target);

            Artisan::call('config:clear');
            $configOutput = Artisan::output();

            Artisan::call('cache:clear');
            $cacheOutput = Artisan::output();

            $run->update([
                'status' => 'succeeded',
                'exit_code' => 0,
                'output' => mb_substr(trim($configOutput."\n".$cacheOutput), 0, 10000),
                'duration_ms' => $this->durationInMilliseconds($startedAt),
                'finished_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $run->update([
                'status' => 'failed',
                'error' => mb_substr($exception->getMessage(), 0, 10000),
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
}
