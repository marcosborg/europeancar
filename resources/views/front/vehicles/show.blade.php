@php
    $galleryMedia = $vehicle->getMedia('vehicle_main')
        ->merge($vehicle->getMedia('vehicle_gallery'))
        ->values();
@endphp

@if($galleryMedia->isNotEmpty())
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <style>
            .vehicle-gallery {
                min-width: 0;
                width: 100%;
            }

            .vehicle-gallery .swiper {
                width: 100%;
            }

            .vehicle-gallery-main {
                aspect-ratio: 4 / 3;
                background: #F5F7FA;
                border-radius: 1rem;
                max-height: 620px;
                overflow: hidden;
            }

            .vehicle-gallery-main .swiper-slide,
            .vehicle-gallery-thumbs .swiper-slide {
                background-position: center;
                background-size: cover;
            }

            .vehicle-gallery-main .swiper-slide img,
            .vehicle-gallery-thumbs .swiper-slide img {
                display: block;
                height: 100%;
                object-fit: cover;
                width: 100%;
            }

            .vehicle-gallery-thumbs {
                box-sizing: border-box;
                height: 96px;
                margin-top: 1rem;
            }

            .vehicle-gallery-thumbs .swiper-slide {
                border: 1px solid #e2e8f0;
                border-radius: .5rem;
                cursor: pointer;
                height: 100%;
                opacity: .45;
                overflow: hidden;
                transition: border-color .2s ease, opacity .2s ease;
            }

            .vehicle-gallery-thumbs .swiper-slide-thumb-active {
                border-color: #F7B500;
                opacity: 1;
            }

            .vehicle-gallery .swiper-button-next,
            .vehicle-gallery .swiper-button-prev {
                --swiper-navigation-color: #002B6B;
                --swiper-navigation-size: 1.25rem;
                background: rgba(255, 255, 255, .92);
                border-radius: 999px;
                box-shadow: 0 10px 25px rgba(15, 23, 42, .14);
                height: 2.5rem;
                width: 2.5rem;
            }
        </style>
    @endpush

    @if($galleryMedia->count() > 1)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const thumbs = new Swiper('.vehicle-gallery-thumbs', {
                        spaceBetween: 10,
                        slidesPerView: 4,
                        freeMode: true,
                        watchSlidesProgress: true,
                        breakpoints: {
                            0: { slidesPerView: 3 },
                            640: { slidesPerView: 4 },
                            1024: { slidesPerView: 5 },
                        },
                    });

                    new Swiper('.vehicle-gallery-main', {
                        spaceBetween: 10,
                        navigation: {
                            nextEl: '.vehicle-gallery-next',
                            prevEl: '.vehicle-gallery-prev',
                        },
                        thumbs: {
                            swiper: thumbs,
                        },
                    });
                });
            </script>
        @endpush
    @endif
@endif

<x-front.layouts.app :locale="$locale" :title="$translation->meta_title ?: $translation->title" :description="$translation->meta_description" :image="$vehicle->mainImageUrl()">
    <div class="border-b border-slate-100 bg-brand-light"><div class="mx-auto max-w-7xl px-4 py-4 text-xs font-semibold text-slate-500 sm:px-6 lg:px-8"><a class="hover:text-brand-navy" href="{{ $locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']) }}">{{ $locale === 'en' ? 'Vehicles' : 'Viaturas' }}</a><span class="mx-2">/</span><span class="text-brand-navy">{{ $vehicle->brand?->name }} {{ $vehicle->carModel?->name }}</span></div></div>
    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[1.2fr_.8fr] lg:px-8 lg:py-14">
        <div class="vehicle-gallery">
            <div>
                @if($galleryMedia->isNotEmpty())
                    <div class="swiper vehicle-gallery-main">
                        <div class="swiper-wrapper">
                            @foreach($galleryMedia as $media)
                                <div class="swiper-slide">
                                    <img src="{{ $media->getUrl() }}" alt="{{ $translation->title }} {{ $loop->iteration }}">
                                </div>
                            @endforeach
                        </div>

                        @if($galleryMedia->count() > 1)
                            <div class="swiper-button-next vehicle-gallery-next"></div>
                            <div class="swiper-button-prev vehicle-gallery-prev"></div>
                        @endif
                    </div>

                    @if($galleryMedia->count() > 1)
                        <div class="swiper vehicle-gallery-thumbs">
                            <div class="swiper-wrapper">
                                @foreach($galleryMedia as $media)
                                    <div class="swiper-slide">
                                        <img src="{{ $media->getAvailableUrl(['card']) }}" alt="{{ $translation->title }} {{ $loop->iteration }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="grid aspect-video place-items-center text-[#002B6B]">European Car</div>
                @endif
            </div>
        </div>
        <aside class="self-start rounded-3xl border border-slate-200 bg-white p-7 shadow-[0_24px_70px_-35px_rgba(0,30,74,0.45)] lg:sticky lg:top-36">
            <div class="flex items-center justify-between gap-3"><p class="text-xs font-extrabold uppercase tracking-[0.2em] text-brand-navy/60">{{ $vehicle->brand?->name }} · {{ $vehicle->carModel?->name }}</p><span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-emerald-700">{{ $locale === 'en' ? 'Available' : 'Disponível' }}</span></div>
            <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight text-brand-deep sm:text-4xl">{{ $translation->title }}</h1>
            <div class="mt-6 grid grid-cols-2 gap-px overflow-hidden rounded-2xl bg-slate-200 text-sm"><div class="bg-brand-light p-4"><span class="block text-xs text-slate-500">{{ $locale === 'en' ? 'Year' : 'Ano' }}</span><strong class="mt-1 block text-brand-navy">{{ $vehicle->year }}</strong></div><div class="bg-brand-light p-4"><span class="block text-xs text-slate-500">{{ $locale === 'en' ? 'Mileage' : 'Quilómetros' }}</span><strong class="mt-1 block text-brand-navy">{{ number_format((int) $vehicle->mileage, 0, ',', ' ') }} km</strong></div><div class="bg-brand-light p-4"><span class="block text-xs text-slate-500">{{ $locale === 'en' ? 'Fuel' : 'Combustível' }}</span><strong class="mt-1 block text-brand-navy">{{ $vehicle->fuel_type }}</strong></div><div class="bg-brand-light p-4"><span class="block text-xs text-slate-500">{{ $locale === 'en' ? 'Gearbox' : 'Caixa' }}</span><strong class="mt-1 block text-brand-navy">{{ $vehicle->transmission }}</strong></div></div>
            <div class="mt-7 border-t border-slate-100 pt-6"><span class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">{{ $locale === 'en' ? 'Sale price' : 'Preço de venda' }}</span><div class="mt-1 text-4xl font-black text-brand-navy">{{ $vehicle->price_on_request ? ($locale === 'en' ? 'On request' : 'Sob consulta') : number_format((float) $vehicle->sale_price, 0, ',', ' ').' €' }}</div></div>
            <div class="mt-7 grid gap-3">
                <a class="rounded-full bg-brand-navy px-5 py-4 text-center font-extrabold text-white transition hover:bg-brand-deep" href="#contact">{{ $locale === 'en' ? 'Request contact' : 'Pedir contacto' }}</a>
                <a class="rounded-full bg-brand-gold px-5 py-4 text-center font-extrabold text-brand-deep transition hover:bg-amber-400" href="#financing">{{ $locale === 'en' ? 'Financing request' : 'Pedido de financiamento' }}</a>
            </div>
        </aside>
    </section>
    <section class="mx-auto grid max-w-7xl gap-10 px-4 pb-20 sm:px-6 lg:grid-cols-[1fr_.8fr] lg:px-8">
        <div><p class="text-xs font-extrabold uppercase tracking-[0.2em] text-brand-navy/60">{{ $locale === 'en' ? 'About this vehicle' : 'Sobre esta viatura' }}</p><h2 class="mt-3 text-3xl font-black text-brand-deep">{{ $locale === 'en' ? 'Description' : 'Descrição' }}</h2><div class="prose prose-slate mt-6 max-w-none leading-7">{!! $translation->description !!}</div></div>
        <div class="rounded-3xl bg-brand-light p-7">
            <h2 class="text-2xl font-black text-brand-deep">{{ $locale === 'en' ? 'Technical data' : 'Dados técnicos' }}</h2>
            @php($technicalFields = [
                'body_type' => [$locale === 'en' ? 'Body type' : 'Carroçaria', null],
                'fuel_type' => [$locale === 'en' ? 'Fuel' : 'Combustível', null],
                'transmission' => [$locale === 'en' ? 'Gearbox' : 'Caixa', null],
                'power_hp' => [$locale === 'en' ? 'Power' : 'Potência', ' cv'],
                'doors' => [$locale === 'en' ? 'Doors' : 'Portas', null],
                'seats' => [$locale === 'en' ? 'Seats' : 'Lugares', null],
                'origin_country' => [$locale === 'en' ? 'Origin' : 'Origem', null],
                'warranty_months' => [$locale === 'en' ? 'Warranty' : 'Garantia', $locale === 'en' ? ' months' : ' meses'],
            ])
            <dl class="mt-6 grid grid-cols-2 gap-x-6 gap-y-5 text-sm">
                @foreach($technicalFields as $field => [$label, $suffix])
                    @if(filled($vehicle->{$field}))<div class="border-b border-slate-200 pb-3"><dt class="text-slate-500">{{ $label }}</dt><dd class="mt-1 font-extrabold text-brand-navy">{{ $vehicle->{$field} }}{{ $suffix }}</dd></div>@endif
                @endforeach
            </dl>
        </div>
    </section>
    <section class="mx-auto grid max-w-7xl gap-10 px-4 pb-16 lg:grid-cols-2">
        <div id="contact" class="scroll-mt-40 rounded-3xl bg-brand-navy p-6 text-white shadow-[0_24px_70px_-35px_rgba(0,30,74,0.6)] sm:p-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-gold">{{ $locale === 'en' ? 'Dedicated support' : 'Apoio dedicado' }}</p><h2 class="mt-2 text-3xl font-black">{{ $locale === 'en' ? 'Contact us' : 'Contacte-nos' }}</h2><livewire:contact-form :locale="$locale" :vehicle-id="$vehicle->id" /></div>
        <div id="financing" class="scroll-mt-40 rounded-3xl border border-slate-200 bg-brand-light p-6 sm:p-8"><p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-navy/60">{{ $locale === 'en' ? 'Tailored solution' : 'Solução personalizada' }}</p><h2 class="mt-2 text-3xl font-black text-brand-deep">{{ $locale === 'en' ? 'Financing' : 'Financiamento' }}</h2><livewire:financing-form :locale="$locale" :vehicle-id="$vehicle->id" /></div>
    </section>
    @if($similarVehicles->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 pb-16"><h2 class="mb-6 text-2xl font-black text-[#002B6B]">{{ $locale === 'en' ? 'Similar vehicles' : 'Viaturas semelhantes' }}</h2><div class="grid gap-6 md:grid-cols-3">@foreach($similarVehicles as $similar)<x-vehicle-card :vehicle="$similar" :locale="$locale" />@endforeach</div></section>
    @endif
</x-front.layouts.app>
