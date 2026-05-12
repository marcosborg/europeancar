@php
    $isProduction = app(\App\Services\SystemTools\EnvironmentSwitcher::class)->isProduction();
@endphp

<div style="display: flex; align-items: center; padding-inline: .75rem;">
    <x-filament::badge :color="$isProduction ? 'danger' : 'success'">
        {{ $isProduction ? 'PRODUCTION' : 'SANDBOX' }}
    </x-filament::badge>
</div>
