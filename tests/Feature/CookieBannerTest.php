<?php

use Livewire\Livewire;

test('cookie banner keeps its livewire root after accepting cookies', function (): void {
    Livewire::test('cookie-banner', ['locale' => 'pt'])
        ->assertSet('visible', true)
        ->call('acceptAll')
        ->assertSet('visible', false)
        ->assertDispatched('cookie-consent', analytics: true, marketing: true, locale: 'pt');
});

test('cookie banner keeps its livewire root after rejecting cookies', function (): void {
    Livewire::test('cookie-banner', ['locale' => 'en'])
        ->assertSet('visible', true)
        ->call('reject')
        ->assertSet('visible', false)
        ->assertDispatched('cookie-consent', analytics: false, marketing: false, locale: 'en');
});
