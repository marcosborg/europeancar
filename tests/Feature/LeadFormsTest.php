<?php

use App\Models\Lead;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('contact endpoint component persistence can create a lead', function () {
    Lead::query()->create([
        'name' => 'Cliente Teste',
        'email' => 'cliente@example.com',
        'phone' => '+351900000000',
        'type' => 'contact',
        'status' => 'new',
        'message' => 'Quero saber mais.',
        'consented_at' => now(),
    ]);

    expect(Lead::query()->where('type', 'contact')->where('email', 'cliente@example.com')->exists())->toBeTrue();
});

test('financing request can be stored as lead', function () {
    Lead::query()->create([
        'name' => 'Cliente Financiamento',
        'email' => 'finance@example.com',
        'type' => 'financing',
        'status' => 'new',
        'down_payment' => 5000,
        'desired_term_months' => 60,
        'consented_at' => now(),
    ]);

    expect(Lead::query()->where('type', 'financing')->where('desired_term_months', 60)->exists())->toBeTrue();
});
