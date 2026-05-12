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
    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-10 lg:grid-cols-[1.2fr_.8fr]">
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
