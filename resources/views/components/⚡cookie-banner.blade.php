<?php

use Livewire\Component;

new class extends Component
{
    public string $locale = 'pt';
    public bool $visible = false;

    public function mount(string $locale = 'pt'): void
    {
        $this->locale = $locale;
        $this->visible = ! request()->cookie('cookie_consent');
    }

    public function acceptAll(): void
    {
        $this->dispatch('cookie-consent', analytics: true, marketing: true, locale: $this->locale);
        $this->visible = false;
    }

    public function reject(): void
    {
        $this->dispatch('cookie-consent', analytics: false, marketing: false, locale: $this->locale);
        $this->visible = false;
    }
};
?>

@if($visible)
    <div class="fixed inset-x-4 bottom-4 z-50 mx-auto max-w-4xl rounded-2xl bg-white p-5 shadow-2xl ring-1 ring-slate-200" x-data @cookie-consent.window="fetch('{{ route('cookies.consent') }}', {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: JSON.stringify({locale: $event.detail.locale, analytics: $event.detail.analytics, marketing: $event.detail.marketing})})">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <p class="text-sm text-[#555555]">{{ $locale === 'en' ? 'We use necessary cookies and, with your consent, analytics and marketing cookies.' : 'Usamos cookies necessários e, com o seu consentimento, cookies analíticos e de marketing.' }}</p>
            <div class="flex gap-2">
                <button wire:click="reject" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold text-[#002B6B]">{{ $locale === 'en' ? 'Reject' : 'Rejeitar' }}</button>
                <button wire:click="acceptAll" class="rounded-lg bg-[#002B6B] px-4 py-2 text-sm font-bold text-white">{{ $locale === 'en' ? 'Accept all' : 'Aceitar todos' }}</button>
            </div>
        </div>
    </div>
@endif
