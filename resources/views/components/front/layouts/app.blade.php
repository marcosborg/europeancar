@props([
    'locale' => app()->getLocale(),
    'title' => null,
    'description' => null,
    'image' => null,
])
@php
    $settings = \App\Models\SiteSetting::current();
    $siteTitle = $settings->site_name;
    $metaTitle = $title ? $title.' | '.$siteTitle : $siteTitle.' | '.$settings->slogan;
    $metaDescription = $description ?: ($settings->seo_defaults[$locale] ?? $settings->slogan);
@endphp
<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="pt" href="{{ url('/pt') }}">
    <link rel="alternate" hreflang="en" href="{{ url('/en') }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($image)<meta property="og:image" content="{{ $image }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#FFFFFF] text-[#1f2937] antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-5">
            <a href="{{ url('/'.$locale) }}" class="flex items-center gap-3 py-2">
                <img src="{{ asset('assets/img/logo.png') }}" class="block h-auto max-w-[45vw]" style="width: 220px;" alt="{{ $siteTitle }}">
            </a>
            <nav class="hidden items-center gap-6 text-sm font-semibold text-[#002B6B] md:flex">
                <a href="{{ $locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']) }}">{{ $locale === 'en' ? 'Buy' : 'Comprar' }}</a>
                <a href="{{ $locale === 'en' ? route('vehicles.rent.en', ['locale' => 'en']) : route('vehicles.rent.pt', ['locale' => 'pt']) }}">{{ $locale === 'en' ? 'Rent' : 'Alugar' }}</a>
                <a href="{{ url('/'.$locale.'/financiamento') }}">{{ $locale === 'en' ? 'Financing' : 'Financiamento' }}</a>
                <a href="{{ url('/'.$locale.'/contactos') }}">{{ $locale === 'en' ? 'Contact' : 'Contactos' }}</a>
            </nav>
            <div class="flex items-center gap-2 text-sm">
                <a class="rounded-full px-3 py-1 text-[#002B6B] ring-1 ring-slate-200" href="{{ url('/pt') }}">PT</a>
                <a class="rounded-full px-3 py-1 text-[#002B6B] ring-1 ring-slate-200" href="{{ url('/en') }}">EN</a>
            </div>
        </div>
    </header>
    <main>{{ $slot }}</main>
    <footer class="bg-[#001E4A] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-4">
            <div class="md:col-span-2">
                <h2 class="text-xl font-bold">{{ $siteTitle }}</h2>
                <p class="mt-2 max-w-xl text-sm text-slate-300">{{ $settings->footer_text[$locale] ?? $settings->slogan }}</p>
            </div>
            <div class="text-sm text-slate-300">
                <p>{{ $settings->legal_company_name }}</p>
                <p>{{ $settings->tax_number ? 'NIF '.$settings->tax_number : '' }}</p>
                <p>{{ $settings->address }}</p>
            </div>
            <div class="grid gap-1 text-sm text-slate-300">
                <a href="mailto:{{ $settings->primary_email }}">{{ $settings->primary_email }}</a>
                <a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a>
                <a href="{{ url('/'.$locale.'/politica-de-privacidade') }}">{{ $locale === 'en' ? 'Privacy Policy' : 'Política de Privacidade' }}</a>
                <a href="{{ url('/'.$locale.'/politica-de-cookies') }}">{{ $locale === 'en' ? 'Cookie Policy' : 'Política de Cookies' }}</a>
                <a href="{{ url('/'.$locale.'/termos-e-condicoes') }}">{{ $locale === 'en' ? 'Terms' : 'Termos e Condições' }}</a>
            </div>
        </div>
    </footer>
    <livewire:cookie-banner :locale="$locale" />
    @livewireScripts
</body>
</html>
