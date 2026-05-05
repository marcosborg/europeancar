<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('admin is protected for guests', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('super admin can access filament', function () {
    $user = User::query()->where('email', 'marcosborges@netlook.pt')->firstOrFail();

    $this->actingAs($user)->get('/admin')->assertSuccessful();
});

test('readonly role can access panel but cannot manage vehicles', function () {
    $user = User::factory()->create(['email' => 'readonly@example.com']);
    $user->assignRole('readonly');

    expect($user->canAccessPanel(filament()->getPanel('admin')))->toBeTrue()
        ->and($user->can('create', App\Models\Vehicle::class))->toBeFalse();
});
