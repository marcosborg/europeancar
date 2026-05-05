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

<section class="{{ $compact ? 'pt-4' : 'mx-auto max-w-7xl px-4 py-12' }}">
    <div class="{{ $compact ? 'grid gap-3' : 'mb-8 grid gap-4 rounded-2xl bg-[#F5F7FA] p-5 md:grid-cols-6' }}">
        <select wire:model.live="brand" class="rounded-lg border-slate-200">
            <option value="">{{ $locale === 'en' ? 'Brand' : 'Marca' }}</option>
            @foreach($brands as $brandOption)<option value="{{ $brandOption->id }}">{{ $brandOption->name }}</option>@endforeach
        </select>
        <select wire:model.live="fuel" class="rounded-lg border-slate-200"><option value="">Fuel</option><option>Gasolina</option><option>Diesel</option><option>Híbrido</option><option>Elétrico</option></select>
        <select wire:model.live="transmission" class="rounded-lg border-slate-200"><option value="">{{ $locale === 'en' ? 'Gearbox' : 'Caixa' }}</option><option>Manual</option><option>Automática</option></select>
        <input wire:model.live="minPrice" type="number" placeholder="Min €" class="rounded-lg border-slate-200">
        <input wire:model.live="maxPrice" type="number" placeholder="Max €" class="rounded-lg border-slate-200">
        <select wire:model.live="sort" class="rounded-lg border-slate-200"><option value="recent">{{ $locale === 'en' ? 'Newest' : 'Mais recente' }}</option><option value="price_asc">Preço ↑</option><option value="price_desc">Preço ↓</option><option value="km_asc">Km ↑</option><option value="year_desc">Ano ↓</option><option value="power_desc">Potência ↓</option></select>
    </div>

    <div class="{{ $compact ? 'grid gap-4' : 'grid gap-6 md:grid-cols-2 lg:grid-cols-3' }}">
        @forelse($vehicles as $vehicle)
            <x-vehicle-card :vehicle="$vehicle" :locale="$locale" />
        @empty
            <div class="rounded-xl bg-white p-8 text-[#555555]">{{ $locale === 'en' ? 'No vehicles found.' : 'Nenhuma viatura encontrada.' }}</div>
        @endforelse
    </div>

    @unless($compact)
        <div class="mt-8">{{ $vehicles->links() }}</div>
    @endunless
</section>
