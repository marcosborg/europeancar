<?php

use App\Models\Vehicle;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('it creates a vehicle with translations from seed data', function () {
    $vehicle = Vehicle::query()->with('translations')->first();

    expect($vehicle)->not->toBeNull()
        ->and($vehicle->translations)->toHaveCount(2)
        ->and($vehicle->translation('pt')?->slug)->toBe('bmw-serie-3-touring-2021');
});

test('public listings work in portuguese and english', function () {
    $this->get('/pt/comprar')->assertSuccessful()->assertSee('Viaturas para venda');
    $this->get('/en/buy')->assertSuccessful()->assertSee('Vehicles for sale');
});

test('public vehicle detail works with localized slugs', function () {
    $this->get('/pt/viaturas/bmw-serie-3-touring-2021')->assertSuccessful()->assertSee('BMW Série 3 Touring 2021');
    $this->get('/en/vehicles/bmw-3-series-touring-2021')->assertSuccessful()->assertSee('BMW 3 Series Touring 2021');
});

test('locale home routes work', function () {
    $this->get('/pt')->assertSuccessful()->assertSee('European Car Sales and Rentals');
    $this->get('/en')->assertSuccessful()->assertSee('European Car Sales and Rentals');
});
