<x-front.layouts.app :locale="$locale" :title="$mode === 'rent' ? ($locale === 'en' ? 'Rent' : 'Alugar') : ($locale === 'en' ? 'Buy' : 'Comprar')">
    <section class="bg-[#F5F7FA] py-12">
        <div class="mx-auto max-w-7xl px-4">
            <p class="text-sm font-bold uppercase tracking-wide text-[#F7B500]">European Car</p>
            <h1 class="mt-2 text-4xl font-black text-[#002B6B]">{{ $mode === 'rent' ? ($locale === 'en' ? 'Vehicles for rent' : 'Viaturas para aluguer') : ($locale === 'en' ? 'Vehicles for sale' : 'Viaturas para venda') }}</h1>
        </div>
    </section>
    <livewire:vehicle-listings :locale="$locale" :mode="$mode" />
</x-front.layouts.app>
