<x-front.layouts.app :locale="$locale" :title="$mode === 'rent' ? ($locale === 'en' ? 'Rent' : 'Alugar') : ($locale === 'en' ? 'Buy' : 'Comprar')">
    <section class="relative overflow-hidden bg-brand-deep py-16 text-white sm:py-20">
        <div class="absolute -right-24 -top-24 size-80 rounded-full border border-white/10"></div><div class="absolute right-20 top-10 size-52 rounded-full border border-brand-gold/20"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-brand-gold">European Car Collection</p>
            <h1 class="mt-4 max-w-3xl text-4xl font-black tracking-tight sm:text-5xl">{{ $mode === 'rent' ? ($locale === 'en' ? 'Vehicles for rent' : 'Viaturas para aluguer') : ($locale === 'en' ? 'Vehicles for sale' : 'Viaturas para venda') }}</h1>
            <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300">{{ $locale === 'en' ? 'Explore a carefully selected range of European vehicles, with transparent information and dedicated support.' : 'Explore uma seleção cuidada de viaturas europeias, com informação transparente e acompanhamento dedicado.' }}</p>
        </div>
    </section>
    <livewire:vehicle-listings :locale="$locale" :mode="$mode" />
</x-front.layouts.app>
