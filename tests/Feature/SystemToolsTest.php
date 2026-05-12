<?php

use App\Filament\Pages\SystemTools;
use App\Models\SystemToolRun;
use App\Models\User;
use App\Services\SystemTools\EnvironmentSwitcher;
use App\Services\SystemTools\SystemToolRunner;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

test('guest cannot access system tools page', function (): void {
    $this->get('/admin/system-tools')->assertRedirect('/admin/login');
});

test('admin without system tools permission cannot access system tools page', function (): void {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)->get('/admin/system-tools')->assertForbidden();
});

test('super admin can access system tools page', function (): void {
    $user = User::query()->where('email', 'marcosborges@netlook.pt')->firstOrFail();

    $this->actingAs($user)->get('/admin/system-tools')->assertSuccessful();
});

test('laravel tool runner only executes whitelisted commands and logs run', function (): void {
    $user = User::query()->where('email', 'marcosborges@netlook.pt')->firstOrFail();

    $run = app(SystemToolRunner::class)->run('view_clear', $user->id);

    expect($run->status)->toBe('succeeded')
        ->and($run->tool)->toBe('laravel')
        ->and($run->action)->toBe('view:clear')
        ->and(SystemToolRun::query()->whereKey($run)->exists())->toBeTrue();

    app(SystemToolRunner::class)->run('not_allowed', $user->id);
})->throws(InvalidArgumentException::class);

test('environment switcher logs missing environment file as failed', function (): void {
    File::shouldReceive('exists')
        ->once()
        ->andReturnFalse();

    $user = User::query()->where('email', 'marcosborges@netlook.pt')->firstOrFail();

    $run = app(EnvironmentSwitcher::class)->switchTo('sandbox', $user->id);

    expect($run->status)->toBe('failed')
        ->and($run->tool)->toBe('environment')
        ->and($run->error)->toContain('.env.local');
});

test('database sync is blocked while environment is production', function (): void {
    app()->detectEnvironment(fn (): string => 'production');

    $user = User::query()->where('email', 'marcosborges@netlook.pt')->firstOrFail();

    $this->actingAs($user);

    Livewire::test(SystemTools::class)
        ->call('syncDatabase');

    expect(SystemToolRun::query()
        ->where('tool', 'database')
        ->where('action', 'sync-production-to-sandbox')
        ->where('status', 'failed')
        ->exists())->toBeTrue();
});
