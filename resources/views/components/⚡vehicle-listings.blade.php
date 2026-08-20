<?php

use App\Models\Brand;
use App\Models\Vehicle;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $locale = 'pt';
    public string $mode = 'sale';
    public bool $compact = false;

    #[Url]
    public ?int $brand = null;

    #[Url]
    public ?string $fuel = null;

    #[Url]
    public ?string $transmission = null;

    #[Url]
    public ?int $minPrice = null;

    #[Url]
    public ?int $maxPrice = null;

    #[Url]
    public string $sort = 'recent';

    public function mount(string $locale = 'pt', string $mode = 'sale', bool $compact = false): void
    {
        $this->locale = $locale;
        $this->mode = $mode;
        $this->compact = $compact;
    }

    public function with(): array
    {
        $query = Vehicle::query()->with(['brand', 'carModel', 'translations', 'media'])->published();
        $this->mode === 'rent' ? $query->forRent() : $query->forSale();

        $query
            ->when($this->brand, fn ($query) => $query->where('brand_id', $this->brand))
            ->when($this->fuel, fn ($query) => $query->where('fuel_type', $this->fuel))
            ->when($this->transmission, fn ($query) => $query->where('transmission', $this->transmission))
            ->when($this->minPrice, fn ($query) => $query->where('sale_price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn ($query) => $query->where('sale_price', '<=', $this->maxPrice));

        match ($this->sort) {
            'price_asc' => $query->orderBy('sale_price'),
            'price_desc' => $query->orderByDesc('sale_price'),
            'km_asc' => $query->orderBy('mileage'),
            'year_desc' => $query->orderByDesc('year'),
            'power_desc' => $query->orderByDesc('power_hp'),
            default => $query->latest(),
        };

        return [
            'brands' => Brand::query()->where('is_active', true)->orderBy('name')->get(),
            'vehicles' => $this->compact ? $query->limit(3)->get() : $query->paginate(12),
        ];
    }
};
?>

<section class="{{ $compact ? 'pt-5' : 'mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8' }}">
    <div class="{{ $compact ? 'grid gap-3' : 'relative z-10 mb-10 -mt-24 grid gap-3 rounded-3xl border border-slate-200/80 bg-white p-4 shadow-[0_24px_70px_-30px_rgba(0,30,74,0.4)] sm:p-6 md:grid-cols-2 lg:grid-cols-6' }}">
        <select wire:model.live="brand" aria-label="{{ $locale === 'en' ? 'Brand' : 'Marca' }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-brand-navy outline-none transition focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20">
            <option value="">{{ $locale === 'en' ? 'Brand' : 'Marca' }}</option>
            @foreach($brands as $brandOption)<option value="{{ $brandOption->id }}">{{ $brandOption->name }}</option>@endforeach
        </select>
        <select wire:model.live="fuel" aria-label="{{ $locale === 'en' ? 'Fuel' : 'Combustível' }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-brand-navy outline-none focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20"><option value="">{{ $locale === 'en' ? 'Fuel' : 'Combustível' }}</option><option>Gasolina</option><option>Diesel</option><option>Híbrido</option><option>Elétrico</option></select>
        <select wire:model.live="transmission" aria-label="{{ $locale === 'en' ? 'Gearbox' : 'Caixa' }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-brand-navy outline-none focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20"><option value="">{{ $locale === 'en' ? 'Gearbox' : 'Caixa' }}</option><option>Manual</option><option>Automática</option></select>
        <input wire:model.live="minPrice" type="number" aria-label="{{ $locale === 'en' ? 'Minimum price' : 'Preço mínimo' }}" placeholder="{{ $locale === 'en' ? 'Min price' : 'Preço mín.' }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-brand-navy placeholder:text-slate-400 focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20">
        <input wire:model.live="maxPrice" type="number" aria-label="{{ $locale === 'en' ? 'Maximum price' : 'Preço máximo' }}" placeholder="{{ $locale === 'en' ? 'Max price' : 'Preço máx.' }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-brand-navy placeholder:text-slate-400 focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20">
        <select wire:model.live="sort" aria-label="{{ $locale === 'en' ? 'Sort' : 'Ordenar' }}" class="rounded-xl border border-slate-200 bg-brand-light px-4 py-3 text-sm font-bold text-brand-navy outline-none focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20"><option value="recent">{{ $locale === 'en' ? 'Newest' : 'Mais recente' }}</option><option value="price_asc">Preço ↑</option><option value="price_desc">Preço ↓</option><option value="km_asc">Km ↑</option><option value="year_desc">Ano ↓</option><option value="power_desc">Potência ↓</option></select>
    </div>

    <div class="{{ $compact ? 'grid gap-4' : 'grid gap-7 md:grid-cols-2 lg:grid-cols-3' }}">
        @forelse($vehicles as $vehicle)
            <x-vehicle-card :vehicle="$vehicle" :locale="$locale" />
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-brand-light p-12 text-center text-slate-600">{{ $locale === 'en' ? 'No vehicles match these filters.' : 'Nenhuma viatura corresponde a estes filtros.' }}</div>
        @endforelse
    </div>

    @unless($compact)
        <div class="mt-8">{{ $vehicles->links() }}</div>
    @endunless
</section>
