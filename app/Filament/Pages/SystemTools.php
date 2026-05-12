<?php

namespace App\Filament\Pages;

use App\Models\SystemToolRun;
use App\Services\SystemTools\DatabaseSyncService;
use App\Services\SystemTools\EnvironmentSwitcher;
use App\Services\SystemTools\SystemToolRunner;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;

class SystemTools extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'System Tools';

    protected static string|\UnitEnum|null $navigationGroup = 'Technical';

    protected static ?int $navigationSort = 90;

    protected static ?string $slug = 'system-tools';

    protected static ?string $title = 'System Tools';

    protected string $view = 'filament.pages.system-tools';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('system_tools') ?? false;
    }

    public function environmentLabel(): string
    {
        return app(EnvironmentSwitcher::class)->currentLabel();
    }

    /**
     * @return array<string, array{label: string, command: string, parameters: array<string, mixed>, destructive: bool}>
     */
    public function tools(): array
    {
        return app(SystemToolRunner::class)->tools();
    }

    public function runTool(string $tool): void
    {
        abort_unless(static::canAccess(), 403);

        $run = app(SystemToolRunner::class)->run($tool, auth()->id());

        $this->notifyFromRun($run, 'Laravel command executed.');
    }

    public function switchEnvironment(string $environment): void
    {
        abort_unless(static::canAccess(), 403);

        $run = app(EnvironmentSwitcher::class)->switchTo($environment, auth()->id());

        $this->notifyFromRun($run, 'Environment switched.');

        if ($run->status === 'succeeded') {
            $this->redirect(Filament::getLoginUrl() ?? '/admin/login', navigate: false);
        }
    }

    public function syncDatabase(): void
    {
        abort_unless(static::canAccess(), 403);

        try {
            app(DatabaseSyncService::class)->ensureReadyToRun();
        } catch (\Throwable $exception) {
            SystemToolRun::query()->create([
                'user_id' => auth()->id(),
                'tool' => 'database',
                'action' => 'sync-production-to-sandbox',
                'status' => 'failed',
                'error' => mb_substr($exception->getMessage(), 0, 10000),
                'started_at' => now(),
                'finished_at' => now(),
            ]);

            Notification::make()
                ->danger()
                ->title('Database sync blocked')
                ->body($exception->getMessage())
                ->send();

            return;
        }

        $run = SystemToolRun::query()->create([
            'user_id' => auth()->id(),
            'tool' => 'database',
            'action' => 'sync-production-to-sandbox',
            'status' => 'pending',
        ]);

        app(DatabaseSyncService::class)->run($run);

        $run->refresh();

        $this->notifyFromRun($run, 'Database sync completed.');
    }

    public function recentRuns(): Collection
    {
        return SystemToolRun::query()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();
    }

    private function notifyFromRun(SystemToolRun $run, string $successTitle): void
    {
        $notification = Notification::make()
            ->title($run->status === 'succeeded' ? $successTitle : 'System tool failed')
            ->body($run->error ?: $run->output ?: 'No output returned.');

        if ($run->status === 'succeeded') {
            $notification->success();
        } else {
            $notification->danger();
        }

        $notification->send();
    }
}
