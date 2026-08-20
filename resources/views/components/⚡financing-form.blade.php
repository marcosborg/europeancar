<?php

use App\Models\Lead;
use Livewire\Component;

new class extends Component
{
    public string $locale = 'pt';
    public ?int $vehicleId = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public ?float $downPayment = null;
    public ?int $desiredTermMonths = null;
    public string $message = '';
    public bool $consent = false;
    public bool $sent = false;

    public function submit(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'downPayment' => ['nullable', 'numeric'],
            'desiredTermMonths' => ['nullable', 'integer'],
            'message' => ['nullable', 'string'],
            'consent' => ['accepted'],
        ]);

        Lead::query()->create([
            'vehicle_id' => $this->vehicleId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'down_payment' => $data['downPayment'],
            'desired_term_months' => $data['desiredTermMonths'],
            'message' => $data['message'],
            'type' => 'financing',
            'status' => 'new',
            'consented_at' => now(),
        ]);

        $this->sent = true;
    }
};
?>

<div>
    <form wire:submit="submit" class="mt-6 grid gap-4">
        @if($sent)<div class="rounded-xl bg-emerald-100 px-4 py-3 font-semibold text-emerald-900" role="status">{{ $locale === 'en' ? 'Financing request sent.' : 'Pedido de financiamento enviado.' }}</div>@endif
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach([
                ['name', $locale === 'en' ? 'Name *' : 'Nome *', 'text', 'name'],
                ['email', 'Email *', 'email', 'email'],
                ['phone', $locale === 'en' ? 'Phone' : 'Telefone', 'tel', 'tel'],
                ['downPayment', $locale === 'en' ? 'Down payment (€)' : 'Entrada inicial (€)', 'number', null],
                ['desiredTermMonths', $locale === 'en' ? 'Term (months)' : 'Prazo (meses)', 'number', null],
            ] as [$field, $label, $type, $autocomplete])
                <label class="grid gap-2 text-sm font-semibold text-brand-deep {{ $field === 'desiredTermMonths' ? 'sm:col-span-2' : '' }}">
                    <span>{{ $label }}</span>
                    <input wire:model="{{ $field }}" type="{{ $type }}" @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-brand-deep placeholder:text-slate-400 focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20">
                    @error($field)<span class="text-xs font-medium text-red-700">{{ $message }}</span>@enderror
                </label>
            @endforeach
        </div>
        <label class="grid gap-2 text-sm font-semibold text-brand-deep"><span>{{ $locale === 'en' ? 'Message' : 'Mensagem' }}</span><textarea wire:model="message" rows="4" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-brand-deep focus:border-brand-gold focus:ring-3 focus:ring-brand-gold/20"></textarea>@error('message')<span class="text-xs text-red-700">{{ $message }}</span>@enderror</label>
        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 text-sm leading-6 text-slate-600"><input wire:model="consent" type="checkbox" class="mt-0.5 size-5 shrink-0 accent-brand-gold"><span>{{ $locale === 'en' ? 'I consent to the processing of my data so that I can be contacted.' : 'Autorizo o tratamento dos meus dados para poder ser contactado.' }}</span></label>
        @error('consent')<span class="text-xs font-medium text-red-700">{{ $message }}</span>@enderror
        <button type="submit" wire:loading.attr="disabled" class="rounded-full bg-brand-gold px-6 py-4 font-extrabold text-brand-deep transition hover:bg-amber-400 disabled:opacity-60"><span wire:loading.remove>{{ $locale === 'en' ? 'Request financing' : 'Pedir financiamento' }}</span><span wire:loading>{{ $locale === 'en' ? 'Sending…' : 'A enviar…' }}</span></button>
    </form>
</div>
