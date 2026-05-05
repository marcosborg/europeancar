<x-front.layouts.app :locale="$locale" :title="$translation->meta_title ?: $translation->title" :description="$translation->meta_description" :image="$vehicle->mainImageUrl()">
    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-10 lg:grid-cols-[1.2fr_.8fr]">
        <div>
            <div class="overflow-hidden rounded-2xl bg-[#F5F7FA]">
                @if($vehicle->mainImageUrl())
                    <img src="{{ $vehicle->mainImageUrl() }}" alt="{{ $translation->title }}" class="h-full max-h-[620px] w-full object-cover">
                @else
                    <div class="grid aspect-video place-items-center text-[#002B6B]">European Car</div>
                @endif
            </div>
        </div>
        <aside class="self-start rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-bold uppercase tracking-wide text-[#F7B500]">{{ $vehicle->brand?->name }} {{ $vehicle->carModel?->name }}</p>
            <h1 class="mt-2 text-3xl font-black text-[#002B6B]">{{ $translation->title }}</h1>
            <p class="mt-3 text-[#555555]">{{ $vehicle->year }} · {{ number_format((int) $vehicle->mileage, 0, ',', ' ') }} km · {{ $vehicle->fuel_type }} · {{ $vehicle->transmission }}</p>
            <div class="mt-6 text-3xl font-black text-[#002B6B]">{{ $vehicle->price_on_request ? ($locale === 'en' ? 'Price on request' : 'Preço sob consulta') : number_format((float) $vehicle->sale_price, 0, ',', ' ').'€' }}</div>
            <div class="mt-6 grid gap-3">
                <a class="rounded-lg bg-[#002B6B] px-5 py-3 text-center font-bold text-white" href="#contact">{{ $locale === 'en' ? 'Request contact' : 'Pedir contacto' }}</a>
                <a class="rounded-lg bg-[#F7B500] px-5 py-3 text-center font-bold text-[#001E4A]" href="#financing">{{ $locale === 'en' ? 'Financing request' : 'Pedido de financiamento' }}</a>
            </div>
        </aside>
    </section>
    <section class="mx-auto grid max-w-7xl gap-10 px-4 pb-16 lg:grid-cols-[1fr_.8fr]">
        <div class="prose max-w-none">{!! $translation->description !!}</div>
        <div class="rounded-2xl bg-[#F5F7FA] p-6">
            <h2 class="text-xl font-black text-[#002B6B]">{{ $locale === 'en' ? 'Technical data' : 'Dados técnicos' }}</h2>
            <dl class="mt-5 grid grid-cols-2 gap-4 text-sm">
                @foreach(['body_type','fuel_type','transmission','power_hp','doors','seats','origin_country','warranty_months'] as $field)
                    <div><dt class="text-[#555555]">{{ str_replace('_', ' ', $field) }}</dt><dd class="font-bold text-[#002B6B]">{{ $vehicle->{$field} }}</dd></div>
                @endforeach
            </dl>
        </div>
    </section>
    <section class="mx-auto grid max-w-7xl gap-10 px-4 pb-16 lg:grid-cols-2">
        <div id="contact" class="rounded-2xl bg-[#002B6B] p-8 text-white"><h2 class="text-2xl font-black">{{ $locale === 'en' ? 'Contact us' : 'Contacte-nos' }}</h2><livewire:contact-form :locale="$locale" :vehicle-id="$vehicle->id" /></div>
        <div id="financing" class="rounded-2xl bg-[#F5F7FA] p-8"><h2 class="text-2xl font-black text-[#002B6B]">{{ $locale === 'en' ? 'Financing' : 'Financiamento' }}</h2><livewire:financing-form :locale="$locale" :vehicle-id="$vehicle->id" /></div>
    </section>
    @if($similarVehicles->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 pb-16"><h2 class="mb-6 text-2xl font-black text-[#002B6B]">{{ $locale === 'en' ? 'Similar vehicles' : 'Viaturas semelhantes' }}</h2><div class="grid gap-6 md:grid-cols-3">@foreach($similarVehicles as $similar)<x-vehicle-card :vehicle="$similar" :locale="$locale" />@endforeach</div></section>
    @endif
</x-front.layouts.app>
