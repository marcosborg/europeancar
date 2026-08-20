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
    @stack('styles')
</head>
<body class="bg-white text-slate-900 antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 shadow-[0_8px_30px_rgba(0,30,74,0.06)] backdrop-blur-xl" x-data="{ menuOpen: false }">
        <div class="border-b border-slate-100 bg-brand-deep text-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.14em] sm:px-6 lg:px-8">
                <span class="text-white/70">{{ $locale === 'en' ? 'Selected European vehicles' : 'Viaturas europeias selecionadas' }}</span>
                <a href="tel:{{ $settings->phone }}" class="transition hover:text-brand-gold">{{ $settings->phone }}</a>
            </div>
        </div>
        <div class="mx-auto flex h-24 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/'.$locale) }}" class="shrink-0" aria-label="{{ $siteTitle }}">
                <img src="{{ asset('assets/img/logo.png') }}" class="h-auto w-48 sm:w-56" alt="{{ $siteTitle }}">
            </a>
            <nav class="hidden items-center gap-1 lg:flex" aria-label="{{ $locale === 'en' ? 'Main navigation' : 'Navegação principal' }}">
                @foreach([
                    [$locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']), $locale === 'en' ? 'Buy' : 'Comprar'],
                    [$locale === 'en' ? route('vehicles.rent.en', ['locale' => 'en']) : route('vehicles.rent.pt', ['locale' => 'pt']), $locale === 'en' ? 'Rent' : 'Alugar'],
                    [url('/'.$locale.'/financiamento'), $locale === 'en' ? 'Financing' : 'Financiamento'],
                    [url('/'.$locale.'/contactos'), $locale === 'en' ? 'Contact' : 'Contactos'],
                ] as [$href, $label])
                    <a href="{{ $href }}" class="rounded-full px-4 py-2.5 text-sm font-bold text-brand-navy transition hover:bg-brand-light hover:text-brand-deep">{{ $label }}</a>
                @endforeach
            </nav>
            <div class="flex items-center gap-2">
                <div class="hidden items-center rounded-full border border-slate-200 bg-brand-light p-1 sm:flex">
                    <a class="rounded-full px-3 py-1.5 text-xs font-bold {{ $locale === 'pt' ? 'bg-brand-navy text-white shadow-sm' : 'text-brand-navy' }}" href="{{ url('/pt') }}">PT</a>
                    <a class="rounded-full px-3 py-1.5 text-xs font-bold {{ $locale === 'en' ? 'bg-brand-navy text-white shadow-sm' : 'text-brand-navy' }}" href="{{ url('/en') }}">EN</a>
                </div>
                <a href="{{ url('/'.$locale.'/contactos') }}" class="hidden rounded-full bg-brand-gold px-5 py-3 text-sm font-extrabold text-brand-deep shadow-sm transition hover:-translate-y-0.5 hover:bg-amber-400 xl:inline-flex">
                    {{ $locale === 'en' ? 'Talk to us' : 'Fale connosco' }}
                </a>
                <button type="button" class="grid size-11 place-items-center rounded-full border border-slate-200 text-brand-navy lg:hidden" @click="menuOpen = ! menuOpen" :aria-expanded="menuOpen" aria-label="Menu">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>
        <div x-cloak x-show="menuOpen" x-transition class="border-t border-slate-100 bg-white px-4 pb-5 lg:hidden">
            <nav class="mx-auto grid max-w-7xl gap-1 pt-3 text-sm font-bold text-brand-navy">
                <a class="rounded-xl px-4 py-3 hover:bg-brand-light" href="{{ $locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']) }}">{{ $locale === 'en' ? 'Buy' : 'Comprar' }}</a>
                <a class="rounded-xl px-4 py-3 hover:bg-brand-light" href="{{ $locale === 'en' ? route('vehicles.rent.en', ['locale' => 'en']) : route('vehicles.rent.pt', ['locale' => 'pt']) }}">{{ $locale === 'en' ? 'Rent' : 'Alugar' }}</a>
                <a class="rounded-xl px-4 py-3 hover:bg-brand-light" href="{{ url('/'.$locale.'/financiamento') }}">{{ $locale === 'en' ? 'Financing' : 'Financiamento' }}</a>
                <a class="rounded-xl px-4 py-3 hover:bg-brand-light" href="{{ url('/'.$locale.'/contactos') }}">{{ $locale === 'en' ? 'Contact' : 'Contactos' }}</a>
                <div class="mt-2 flex gap-2 border-t border-slate-100 pt-4"><a class="rounded-full border border-slate-200 px-4 py-2" href="{{ url('/pt') }}">PT</a><a class="rounded-full border border-slate-200 px-4 py-2" href="{{ url('/en') }}">EN</a></div>
            </nav>
        </div>
    </header>
    <main>{{ $slot }}</main>
    <footer class="relative overflow-hidden bg-brand-deep text-white">
        <div class="absolute -right-32 -top-32 size-96 rounded-full border border-white/5"></div>
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 md:grid-cols-4 lg:px-8">
            <div class="md:col-span-2">
                <img src="{{ asset('assets/img/logo.png') }}" class="w-56 rounded-xl bg-white p-3" alt="{{ $siteTitle }}">
                <p class="mt-5 max-w-xl text-sm leading-7 text-slate-300">{{ $settings->footer_text[$locale] ?? $settings->slogan }}</p>
            </div>
            <div class="text-sm leading-7 text-slate-300">
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-brand-gold">{{ $locale === 'en' ? 'Company' : 'Empresa' }}</p>
                <p class="font-semibold text-white">{{ $settings->legal_company_name }}</p>
                @if($settings->tax_number)<p>NIF {{ $settings->tax_number }}</p>@endif
                <p>{{ $settings->address }}</p>
            </div>
            <div class="grid content-start gap-2 text-sm text-slate-300">
                <p class="mb-1 text-xs font-bold uppercase tracking-[0.2em] text-brand-gold">{{ $locale === 'en' ? 'Contact' : 'Contactos' }}</p>
                <a class="transition hover:text-white" href="mailto:{{ $settings->primary_email }}">{{ $settings->primary_email }}</a>
                <a class="transition hover:text-white" href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a>
                <a class="transition hover:text-white" href="{{ url('/'.$locale.'/politica-de-privacidade') }}">{{ $locale === 'en' ? 'Privacy Policy' : 'Política de Privacidade' }}</a>
                <a class="transition hover:text-white" href="{{ url('/'.$locale.'/politica-de-cookies') }}">{{ $locale === 'en' ? 'Cookie Policy' : 'Política de Cookies' }}</a>
                <a class="transition hover:text-white" href="{{ url('/'.$locale.'/termos-e-condicoes') }}">{{ $locale === 'en' ? 'Terms' : 'Termos e Condições' }}</a>
            </div>
        </div>
        <div class="border-t border-white/10"><div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-5 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8"><span>© {{ date('Y') }} {{ $siteTitle }}</span><span>Drive Europe. Choose excellence.</span></div></div>
    </footer>
    <livewire:cookie-banner :locale="$locale" />
    @livewireScripts
    @stack('scripts')
</body>
</html>
