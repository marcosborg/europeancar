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
        return $this->isProduction() ? 'PRODUCTION' : 'SANDBOX';
    }

    public function isProduction(): bool
    {
        return $this->currentEnvironment() === 'production';
    }

    public function currentEnvironment(): string
    {
        return $this->appEnvironmentFromFile(base_path('.env'));
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

            $appEnvironment = $this->appEnvironmentFromFile($source);
            $this->ensureExpectedEnvironment($environment, $appEnvironment, $source);

            $target = base_path('.env');

            if (File::exists($target)) {
                File::copy($target, base_path('.env.backup'));
            }

            File::copy($source, $target);

            Artisan::call('config:clear');
            $configOutput = Artisan::output();

            Artisan::call('cache:clear');
            $cacheOutput = Artisan::output();

            config(['app.env' => $appEnvironment]);
            app()->detectEnvironment(fn (): string => $appEnvironment);

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

    private function appEnvironmentFromFile(string $path): string
    {
        $contents = File::get($path);

        if (! preg_match('/^APP_ENV=(.*)$/m', $contents, $matches)) {
            throw new RuntimeException("Environment file is missing APP_ENV: {$path}");
        }

        return trim($matches[1], " \t\n\r\0\x0B\"'");
    }

    private function ensureExpectedEnvironment(string $targetEnvironment, string $appEnvironment, string $path): void
    {
        if ($targetEnvironment === 'production' && $appEnvironment !== 'production') {
            throw new RuntimeException("Production environment file must define APP_ENV=production: {$path}");
        }

        if ($targetEnvironment === 'sandbox' && $appEnvironment === 'production') {
            throw new RuntimeException("Sandbox environment file cannot define APP_ENV=production: {$path}");
        }
    }
}
