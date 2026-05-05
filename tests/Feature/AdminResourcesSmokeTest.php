<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
    $this->actingAs(User::query()->where('email', 'marcosborges@netlook.pt')->firstOrFail());
});

test('main filament resource pages render', function (string $path) {
    $this->get($path)->assertSuccessful();
})->with([
    '/admin/vehicles',
    '/admin/vehicles/create',
    '/admin/leads',
    '/admin/pages',
    '/admin/menus',
    '/admin/site-settings',
    '/admin/vehicle-features',
    '/admin/users',
]);
