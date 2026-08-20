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

<div x-data @cookie-consent.window="
    document.cookie = `cookie_consent=${crypto.randomUUID()}; Max-Age=31536000; Path=/; SameSite=Lax`;
    fetch('{{ route('cookies.consent') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({locale: $event.detail.locale, analytics: $event.detail.analytics, marketing: $event.detail.marketing}),
    });
">
    @if($visible)
        <div role="dialog" aria-labelledby="cookie-title" class="fixed inset-x-3 bottom-3 z-50 mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_24px_80px_-25px_rgba(0,30,74,0.5)] sm:inset-x-4 sm:bottom-4">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div><p id="cookie-title" class="font-extrabold text-brand-deep">{{ $locale === 'en' ? 'Your privacy matters' : 'A sua privacidade importa' }}</p><p class="mt-1 max-w-2xl text-sm leading-6 text-slate-600">{{ $locale === 'en' ? 'We use necessary cookies and, with your consent, analytics and marketing cookies.' : 'Usamos cookies necessários e, com o seu consentimento, cookies analíticos e de marketing.' }}</p></div>
                <div class="grid shrink-0 grid-cols-2 gap-2">
                    <button wire:click="reject" class="min-h-11 rounded-full border border-slate-300 px-5 py-2 text-sm font-bold text-brand-navy transition hover:bg-brand-light">{{ $locale === 'en' ? 'Reject' : 'Rejeitar' }}</button>
                    <button wire:click="acceptAll" class="min-h-11 rounded-full bg-brand-navy px-5 py-2 text-sm font-bold text-white transition hover:bg-brand-deep">{{ $locale === 'en' ? 'Accept all' : 'Aceitar todos' }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
