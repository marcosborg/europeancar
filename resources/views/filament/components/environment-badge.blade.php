@php
    $isProduction = app()->isProduction();
@endphp

<div class="flex items-center px-3">
    <span @class([
        'rounded-full px-3 py-1 text-xs font-bold ring-1',
        'bg-red-50 text-red-700 ring-red-200 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/30' => $isProduction,
        'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/30' => ! $isProduction,
    ])>
        {{ $isProduction ? 'PRODUCTION' : 'SANDBOX' }}
    </span>
</div>
