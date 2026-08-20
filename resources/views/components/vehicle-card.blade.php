@props(['vehicle', 'locale' => app()->getLocale()])
@php($translation = $vehicle->translation($locale))
<article class="group overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-[0_18px_50px_-30px_rgba(0,30,74,0.45)] transition duration-300 hover:-translate-y-1.5 hover:border-brand-gold/50 hover:shadow-[0_25px_60px_-25px_rgba(0,30,74,0.4)]">
    <a href="{{ $vehicle->publicUrl($locale) }}" class="block focus:outline-none focus:ring-3 focus:ring-brand-gold/40">
        <div class="relative aspect-[4/3] overflow-hidden bg-brand-light">
            @if($vehicle->mainImageUrl('card'))
                <img src="{{ $vehicle->mainImageUrl('card') }}" alt="{{ $translation?->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
            @else
                <div class="grid h-full place-items-center bg-[#F5F7FA] text-[#002B6B]">European Car</div>
            @endif
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-brand-deep/55 to-transparent"></div>
            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-brand-navy shadow-sm backdrop-blur">{{ $vehicle->origin_country ?: 'Europe' }}</span>
            @if($vehicle->featured)<span class="absolute right-4 top-4 rounded-full bg-brand-gold px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wide text-brand-deep">{{ $locale === 'en' ? 'Featured' : 'Destaque' }}</span>@endif
        </div>
        <div class="space-y-5 p-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-gold">{{ $vehicle->brand?->name }} · {{ $vehicle->carModel?->name }}</p>
                <h3 class="mt-2 min-h-14 text-xl font-extrabold leading-tight text-brand-navy">{{ $translation?->title ?? $vehicle->publicTitle($locale) }}</h3>
                <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 border-y border-slate-100 py-4 text-sm text-slate-600">
                    <span>{{ $vehicle->year }}</span><span>{{ number_format((int) $vehicle->mileage, 0, ',', ' ') }} km</span><span>{{ $vehicle->fuel_type }}</span><span>{{ $vehicle->transmission }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                @if($vehicle->warranty_months)<span class="rounded-full bg-brand-light px-3 py-1.5 font-semibold text-brand-navy">{{ $locale === 'en' ? 'Warranty' : 'Garantia' }}</span>@endif
                @if($vehicle->financing_available)<span class="rounded-full bg-brand-gold/15 px-3 py-1.5 font-semibold text-brand-navy">{{ $locale === 'en' ? 'Financing' : 'Financiamento' }}</span>@endif
            </div>
            <div class="flex items-end justify-between gap-4">
                <div><span class="block text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $locale === 'en' ? 'Price' : 'Preço' }}</span><strong class="text-2xl font-black text-brand-navy">{{ $vehicle->price_on_request ? ($locale === 'en' ? 'On request' : 'Sob consulta') : number_format((float) $vehicle->sale_price, 0, ',', ' ').' €' }}</strong></div>
                <span class="grid size-11 place-items-center rounded-full bg-brand-navy text-white transition group-hover:bg-brand-gold group-hover:text-brand-deep"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></span>
            </div>
        </div>
    </a>
</article>
