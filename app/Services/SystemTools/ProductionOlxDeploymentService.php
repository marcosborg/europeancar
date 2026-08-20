<?php

namespace App\Services\SystemTools;

use App\Models\SystemToolRun;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use RuntimeException;
use Throwable;

class ProductionOlxDeploymentService
{
    private const ACTION = 'deploy-olx-vehicles-2026-08-20';

    public function run(?int $userId): SystemToolRun
    {
        if (! app()->isProduction()) {
            throw new RuntimeException('Esta publicação só pode ser executada no ambiente de produção.');
        }

        $completedRun = SystemToolRun::query()
            ->where('action', self::ACTION)
            ->where('status', 'succeeded')
            ->latest()
            ->first();

        if ($completedRun) {
            return $completedRun;
        }

        $lock = Cache::lock(self::ACTION, 600);

        if (! $lock->get()) {
            throw new RuntimeException('A publicação já está a ser executada. Aguarde alguns minutos.');
        }

        $run = SystemToolRun::query()->create([
            'user_id' => $userId,
            'tool' => 'deployment',
            'action' => self::ACTION,
            'status' => 'running',
            'started_at' => now(),
        ]);

        $startedAt = hrtime(true);

        try {
            $completedSteps = [];

            foreach ($this->steps() as $label => [$command, $parameters]) {
                $exitCode = Artisan::call($command, $parameters);

                if ($exitCode !== 0) {
                    throw new RuntimeException("Falhou a etapa: {$label}.");
                }

                $completedSteps[] = $label;
            }

            $run->update([
                'status' => 'succeeded',
                'exit_code' => 0,
                'output' => 'Concluído: '.implode(', ', $completedSteps).'.',
                'duration_ms' => $this->durationInMilliseconds($startedAt),
                'finished_at' => now(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            $run->update([
                'status' => 'failed',
                'exit_code' => 1,
                'error' => $exception->getMessage(),
                'duration_ms' => $this->durationInMilliseconds($startedAt),
                'finished_at' => now(),
            ]);
        } finally {
            $lock->release();
        }

        return $run->refresh();
    }

    /** @return array<string, array{string, array<string, mixed>}> */
    private function steps(): array
    {
        return [
            'atualização da base de dados' => ['migrate', ['--force' => true]],
            'ligação do armazenamento público' => ['storage:link', []],
            'importação das quatro viaturas e imagens' => ['db:seed', [
                '--class' => 'Database\\Seeders\\ProductionOlxVehiclesSeeder',
                '--force' => true,
            ]],
            'limpeza das caches' => ['optimize:clear', []],
        ];
    }

    private function durationInMilliseconds(int $startedAt): int
    {
        return (int) round((hrtime(true) - $startedAt) / 1_000_000);
    }
}
