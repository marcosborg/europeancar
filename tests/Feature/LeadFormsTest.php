<?php

use App\Models\Lead;
use Database\Seeders\DatabaseSeeder;
use Livewire\Livewire;

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

test('contact form can update and submit through livewire', function (): void {
    Livewire::test('contact-form', ['locale' => 'pt'])
        ->set('name', 'Cliente Livewire')
        ->set('email', 'livewire@example.com')
        ->set('phone', '+351900000000')
        ->set('subject', 'Pedido de contacto')
        ->set('message', 'Gostaria de receber mais informações.')
        ->set('consent', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('sent', true);

    expect(Lead::query()->where('email', 'livewire@example.com')->exists())->toBeTrue();
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
