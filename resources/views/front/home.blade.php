<x-front.layouts.app :locale="$locale" :title="$translation?->meta_title ?: $settings->site_name" :description="$translation?->meta_description">
    <section class="bg-[#001E4A] text-white">
        <div class="mx-auto grid min-h-[620px] max-w-7xl items-center gap-10 px-4 py-16 lg:grid-cols-[1.1fr_.9fr]">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#F7B500]">{{ $settings->slogan }}</p>
                <h1 class="mt-5 max-w-4xl text-4xl font-black leading-tight md:text-6xl">European Car Sales and Rentals</h1>
                <p class="mt-6 max-w-2xl text-lg text-slate-300">{{ $locale === 'en' ? 'Selected European vehicles for sale and rental, managed directly by our team.' : 'Viaturas europeias selecionadas para venda e aluguer, geridas diretamente pela nossa equipa.' }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ $locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']) }}" class="rounded-lg bg-[#F7B500] px-6 py-3 font-bold text-[#001E4A]">{{ $locale === 'en' ? 'Buy a car' : 'Comprar viatura' }}</a>
                    <a href="{{ $locale === 'en' ? route('vehicles.rent.en', ['locale' => 'en']) : route('vehicles.rent.pt', ['locale' => 'pt']) }}" class="rounded-lg border border-white/30 px-6 py-3 font-bold text-white">{{ $locale === 'en' ? 'Rent a car' : 'Alugar viatura' }}</a>
                </div>
            </div>
            <div class="rounded-2xl bg-white p-5 text-[#002B6B] shadow-2xl">
                <h2 class="text-xl font-bold">{{ $locale === 'en' ? 'Find your next vehicle' : 'Encontre a sua próxima viatura' }}</h2>
                <livewire:vehicle-listings :locale="$locale" mode="sale" compact />
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-[#F7B500]">{{ $locale === 'en' ? 'Featured' : 'Destaques' }}</p>
                <h2 class="text-3xl font-black text-[#002B6B]">{{ $locale === 'en' ? 'Selected vehicles' : 'Viaturas selecionadas' }}</h2>
            </div>
            <a href="{{ $locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']) }}" class="text-sm font-bold text-[#002B6B]">{{ $locale === 'en' ? 'View all' : 'Ver todas' }}</a>
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($featuredVehicles as $vehicle)
                <x-vehicle-card :vehicle="$vehicle" :locale="$locale" />
            @empty
                <div class="rounded-xl bg-[#F5F7FA] p-8 text-[#555555]">{{ $locale === 'en' ? 'Vehicles will be published soon.' : 'As viaturas serão publicadas em breve.' }}</div>
            @endforelse
        </div>
    </section>

    <section class="bg-[#F5F7FA] py-16">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 md:grid-cols-4">
            @foreach([
                $locale === 'en' ? 'Selected in Europe' : 'Selecionadas na Europa',
                $locale === 'en' ? 'Warranty available' : 'Garantia disponível',
                $locale === 'en' ? 'Financing support' : 'Financiamento',
                $locale === 'en' ? 'Process guidance' : 'Apoio no processo',
            ] as $item)
                <div class="rounded-xl bg-white p-6 shadow-sm"><div class="mb-4 h-1 w-12 rounded bg-[#F7B500]"></div><h3 class="font-bold text-[#002B6B]">{{ $item }}</h3></div>
            @endforeach
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-16 lg:grid-cols-2">
        <div>
            <h2 class="text-3xl font-black text-[#002B6B]">{{ $locale === 'en' ? 'How it works' : 'Como funciona' }}</h2>
            <div class="mt-6 grid gap-4">
                @foreach(['Escolha a viatura', 'Fale com a equipa', 'Financiamento ou reserva', 'Entrega com acompanhamento'] as $step)
                    <div class="rounded-xl border border-slate-200 p-5 font-semibold">{{ $locale === 'en' ? str_replace(['Escolha a viatura','Fale com a equipa','Financiamento ou reserva','Entrega com acompanhamento'], ['Choose the vehicle','Talk to the team','Financing or booking','Supported delivery'], $step) : $step }}</div>
                @endforeach
            </div>
        </div>
        <div class="rounded-2xl bg-[#002B6B] p-8 text-white">
            <h2 class="text-2xl font-black">{{ $locale === 'en' ? 'Request contact' : 'Pedido de contacto' }}</h2>
            <livewire:contact-form :locale="$locale" />
        </div>
    </section>

    @if($translation?->content)
        <section class="mx-auto max-w-4xl px-4 pb-16 prose prose-slate">{!! $translation->content !!}</section>
    @endif
</x-front.layouts.app>
