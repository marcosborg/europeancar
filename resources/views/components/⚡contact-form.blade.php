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

<div>
<form wire:submit="submit" class="mt-6 grid gap-5">
    @if($sent)
        <div class="rounded-lg bg-emerald-100 px-4 py-3 font-medium text-emerald-900" role="status">
            {{ $locale === 'en' ? 'Request sent.' : 'Pedido enviado.' }}
        </div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="contact-name" class="text-sm font-semibold text-white">{{ $locale === 'en' ? 'Name' : 'Nome' }} *</label>
            <input id="contact-name" wire:model="name" autocomplete="name" class="rounded-lg border border-white/30 bg-white px-4 py-3 text-brand-deep placeholder:text-slate-400 focus:border-brand-gold focus:outline-none focus:ring-3 focus:ring-brand-gold/30" placeholder="{{ $locale === 'en' ? 'Your name' : 'O seu nome' }}">
            @error('name') <p class="text-sm text-amber-200">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-2">
            <label for="contact-email" class="text-sm font-semibold text-white">Email *</label>
            <input id="contact-email" wire:model="email" type="email" autocomplete="email" class="rounded-lg border border-white/30 bg-white px-4 py-3 text-brand-deep placeholder:text-slate-400 focus:border-brand-gold focus:outline-none focus:ring-3 focus:ring-brand-gold/30" placeholder="nome@exemplo.pt">
            @error('email') <p class="text-sm text-amber-200">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-2">
            <label for="contact-phone" class="text-sm font-semibold text-white">{{ $locale === 'en' ? 'Phone' : 'Telefone' }}</label>
            <input id="contact-phone" wire:model="phone" type="tel" autocomplete="tel" class="rounded-lg border border-white/30 bg-white px-4 py-3 text-brand-deep placeholder:text-slate-400 focus:border-brand-gold focus:outline-none focus:ring-3 focus:ring-brand-gold/30" placeholder="+351 900 000 000">
            @error('phone') <p class="text-sm text-amber-200">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-2">
            <label for="contact-subject" class="text-sm font-semibold text-white">{{ $locale === 'en' ? 'Subject' : 'Assunto' }}</label>
            <input id="contact-subject" wire:model="subject" class="rounded-lg border border-white/30 bg-white px-4 py-3 text-brand-deep placeholder:text-slate-400 focus:border-brand-gold focus:outline-none focus:ring-3 focus:ring-brand-gold/30" placeholder="{{ $locale === 'en' ? 'How can we help?' : 'Como podemos ajudar?' }}">
            @error('subject') <p class="text-sm text-amber-200">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label for="contact-message" class="text-sm font-semibold text-white">{{ $locale === 'en' ? 'Message' : 'Mensagem' }}</label>
        <textarea id="contact-message" wire:model="message" class="min-h-32 resize-y rounded-lg border border-white/30 bg-white px-4 py-3 text-brand-deep placeholder:text-slate-400 focus:border-brand-gold focus:outline-none focus:ring-3 focus:ring-brand-gold/30" rows="4" placeholder="{{ $locale === 'en' ? 'Tell us what you are looking for.' : 'Diga-nos o que procura.' }}"></textarea>
        @error('message') <p class="text-sm text-amber-200">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="contact-consent" class="flex cursor-pointer items-start gap-3 rounded-lg border border-white/15 bg-white/5 p-4 text-sm leading-6 text-slate-100">
            <input id="contact-consent" wire:model="consent" type="checkbox" class="mt-0.5 size-5 shrink-0 cursor-pointer rounded border-white/50 bg-white text-brand-gold accent-brand-gold focus:ring-2 focus:ring-brand-gold focus:ring-offset-2 focus:ring-offset-brand-navy">
            <span>{{ $locale === 'en' ? 'I consent to the processing of my data so that I can be contacted.' : 'Autorizo o tratamento dos meus dados para poder ser contactado.' }}</span>
        </label>
        @error('consent') <p class="mt-2 text-sm text-amber-200">{{ $message }}</p> @enderror
    </div>

    <button type="submit" wire:loading.attr="disabled" class="rounded-lg bg-brand-gold px-5 py-3.5 font-bold text-brand-deep transition hover:bg-amber-400 focus:outline-none focus:ring-3 focus:ring-white/50 disabled:cursor-wait disabled:opacity-70">
        <span wire:loading.remove>{{ $locale === 'en' ? 'Send request' : 'Enviar pedido' }}</span>
        <span wire:loading>{{ $locale === 'en' ? 'Sending…' : 'A enviar…' }}</span>
    </button>
</form>
</div>
