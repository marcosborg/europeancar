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
    public string $subject = '';
    public string $message = '';
    public bool $consent = false;
    public bool $sent = false;

    public function submit(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'consent' => ['accepted'],
        ]);

        Lead::query()->create([
            'vehicle_id' => $this->vehicleId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'type' => 'contact',
            'status' => 'new',
            'consented_at' => now(),
        ]);

        $this->reset('name', 'email', 'phone', 'subject', 'message', 'consent');
        $this->sent = true;
    }
};
?>

<form wire:submit="submit" class="mt-5 grid gap-4">
    @if($sent)<div class="rounded-lg bg-green-100 p-3 text-green-800">{{ $locale === 'en' ? 'Request sent.' : 'Pedido enviado.' }}</div>@endif
    <input wire:model="name" class="rounded-lg border-slate-200 text-[#1f2937]" placeholder="{{ $locale === 'en' ? 'Name' : 'Nome' }}">
    <input wire:model="email" class="rounded-lg border-slate-200 text-[#1f2937]" placeholder="Email">
    <input wire:model="phone" class="rounded-lg border-slate-200 text-[#1f2937]" placeholder="{{ $locale === 'en' ? 'Phone' : 'Telefone' }}">
    <input wire:model="subject" class="rounded-lg border-slate-200 text-[#1f2937]" placeholder="{{ $locale === 'en' ? 'Subject' : 'Assunto' }}">
    <textarea wire:model="message" class="rounded-lg border-slate-200 text-[#1f2937]" rows="4" placeholder="{{ $locale === 'en' ? 'Message' : 'Mensagem' }}"></textarea>
    <label class="flex gap-2 text-sm"><input wire:model="consent" type="checkbox"> <span>{{ $locale === 'en' ? 'I consent to data processing.' : 'Autorizo o tratamento dos dados.' }}</span></label>
    <button class="rounded-lg bg-[#F7B500] px-5 py-3 font-bold text-[#001E4A]">{{ $locale === 'en' ? 'Send' : 'Enviar' }}</button>
</form>
