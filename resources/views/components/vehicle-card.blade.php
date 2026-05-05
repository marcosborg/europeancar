@props(['vehicle', 'locale' => app()->getLocale()])
@php($translation = $vehicle->translation($locale))
<article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
    <a href="{{ $vehicle->publicUrl($locale) }}" class="block">
        <div class="aspect-[4/3] bg-slate-100">
            @if($vehicle->mainImageUrl('card'))
                <img src="{{ $vehicle->mainImageUrl('card') }}" alt="{{ $translation?->title }}" class="h-full w-full object-cover">
            @else
                <div class="grid h-full place-items-center bg-[#F5F7FA] text-[#002B6B]">European Car</div>
            @endif
        </div>
        <div class="space-y-4 p-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-[#F7B500]">{{ $vehicle->origin_country ?: 'Europe' }}</p>
                <h3 class="mt-1 text-lg font-bold text-[#002B6B]">{{ $translation?->title ?? $vehicle->publicTitle($locale) }}</h3>
                <p class="text-sm text-[#555555]">{{ $vehicle->year }} · {{ number_format((int) $vehicle->mileage, 0, ',', ' ') }} km · {{ $vehicle->fuel_type }} · {{ $vehicle->transmission }}</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                @if($vehicle->warranty_months)<span class="rounded-full bg-[#F5F7FA] px-3 py-1">Garantia</span>@endif
                @if($vehicle->financing_available)<span class="rounded-full bg-[#F7B500]/15 px-3 py-1 text-[#002B6B]">Financiamento</span>@endif
            </div>
            <div class="flex items-center justify-between gap-4">
                <strong class="text-xl text-[#002B6B]">{{ $vehicle->price_on_request ? ($locale === 'en' ? 'On request' : 'Sob consulta') : number_format((float) $vehicle->sale_price, 0, ',', ' ').'€' }}</strong>
                <span class="rounded-lg bg-[#002B6B] px-4 py-2 text-sm font-semibold text-white">{{ $locale === 'en' ? 'Details' : 'Detalhes' }}</span>
            </div>
        </div>
    </a>
</article>
