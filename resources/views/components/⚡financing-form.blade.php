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

<form wire:submit="submit" class="mt-5 grid gap-4">
    @if($sent)<div class="rounded-lg bg-green-100 p-3 text-green-800">{{ $locale === 'en' ? 'Financing request sent.' : 'Pedido de financiamento enviado.' }}</div>@endif
    <input wire:model="name" class="rounded-lg border-slate-200" placeholder="{{ $locale === 'en' ? 'Name' : 'Nome' }}">
    <input wire:model="email" class="rounded-lg border-slate-200" placeholder="Email">
    <input wire:model="phone" class="rounded-lg border-slate-200" placeholder="{{ $locale === 'en' ? 'Phone' : 'Telefone' }}">
    <input wire:model="downPayment" type="number" class="rounded-lg border-slate-200" placeholder="{{ $locale === 'en' ? 'Down payment' : 'Entrada inicial' }}">
    <input wire:model="desiredTermMonths" type="number" class="rounded-lg border-slate-200" placeholder="{{ $locale === 'en' ? 'Term months' : 'Prazo em meses' }}">
    <textarea wire:model="message" rows="4" class="rounded-lg border-slate-200" placeholder="{{ $locale === 'en' ? 'Message' : 'Mensagem' }}"></textarea>
    <label class="flex gap-2 text-sm"><input wire:model="consent" type="checkbox"> <span>{{ $locale === 'en' ? 'I consent to data processing.' : 'Autorizo o tratamento dos dados.' }}</span></label>
    <button class="rounded-lg bg-[#002B6B] px-5 py-3 font-bold text-white">{{ $locale === 'en' ? 'Submit' : 'Submeter' }}</button>
</form>
