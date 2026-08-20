<x-front.layouts.app :locale="$locale" :title="$translation?->meta_title ?: $settings->site_name" :description="$translation?->meta_description">
    @php($heroVehicle = $featuredVehicles->first())

    <section class="relative isolate overflow-hidden bg-brand-deep text-white">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, #F7B500 0, transparent 24%), radial-gradient(circle at 90% 80%, #002B6B 0, transparent 38%);"></div>
        <div class="relative mx-auto grid min-h-[680px] max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:px-8 lg:py-20">
            <div class="relative z-10">
                <p class="text-xs font-extrabold uppercase tracking-[0.28em] text-brand-gold">{{ $settings->slogan }}</p>
                <h1 class="mt-6 max-w-3xl text-5xl font-black leading-[1.02] tracking-[-0.04em] sm:text-6xl lg:text-7xl">
                    {{ $locale === 'en' ? 'European cars. Selected with confidence.' : 'Viaturas europeias. Escolhidas com confiança.' }}
                </h1>
                <p class="mt-7 max-w-xl text-lg leading-8 text-slate-300">{{ $locale === 'en' ? 'Premium vehicle sales and rentals with transparent information, tailored support and a truly European selection.' : 'Venda e aluguer de viaturas premium, com informação transparente, acompanhamento personalizado e uma seleção verdadeiramente europeia.' }}</p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="{{ $locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']) }}" class="rounded-full bg-brand-gold px-7 py-4 text-sm font-extrabold text-brand-deep shadow-lg shadow-brand-gold/10 transition hover:-translate-y-0.5 hover:bg-amber-400">{{ $locale === 'en' ? 'Explore vehicles' : 'Explorar viaturas' }}</a>
                    <a href="{{ url('/'.$locale.'/contactos') }}" class="rounded-full border border-white/25 bg-white/5 px-7 py-4 text-sm font-extrabold text-white backdrop-blur transition hover:border-white/50 hover:bg-white/10">{{ $locale === 'en' ? 'Talk to our team' : 'Falar com a equipa' }}</a>
                </div>
                <div class="mt-12 grid max-w-xl grid-cols-3 gap-4 border-t border-white/15 pt-7">
                    <div><strong class="block text-2xl font-black text-white">EU</strong><span class="text-xs text-slate-400">{{ $locale === 'en' ? 'Selected origin' : 'Origem selecionada' }}</span></div>
                    <div><strong class="block text-2xl font-black text-white">PT/EN</strong><span class="text-xs text-slate-400">{{ $locale === 'en' ? 'Personal support' : 'Apoio personalizado' }}</span></div>
                    <div><strong class="block text-2xl font-black text-white">360°</strong><span class="text-xs text-slate-400">{{ $locale === 'en' ? 'Purchase guidance' : 'Apoio na compra' }}</span></div>
                </div>
            </div>

            <div class="relative min-h-[440px] lg:min-h-[560px]">
                <div class="absolute -inset-5 rotate-2 rounded-[2rem] border border-white/10"></div>
                <div class="absolute inset-0 overflow-hidden rounded-[2rem] bg-brand-navy shadow-2xl shadow-black/30">
                    @if($heroVehicle?->mainImageUrl())
                        <img src="{{ $heroVehicle->mainImageUrl() }}" alt="{{ $heroVehicle->publicTitle($locale) }}" class="h-full w-full object-cover">
                    @else
                        <div class="grid h-full place-items-center text-2xl font-black">European Car</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-deep via-brand-deep/10 to-transparent"></div>
                    @if($heroVehicle)
                        <div class="absolute inset-x-0 bottom-0 p-7 sm:p-9">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-gold">{{ $locale === 'en' ? 'Featured vehicle' : 'Viatura em destaque' }}</p>
                            <div class="mt-3 flex items-end justify-between gap-5">
                                <div><h2 class="text-2xl font-black sm:text-3xl">{{ $heroVehicle->publicTitle($locale) }}</h2><p class="mt-2 text-sm text-slate-300">{{ $heroVehicle->year }} · {{ number_format((int) $heroVehicle->mileage, 0, ',', ' ') }} km · {{ $heroVehicle->fuel_type }}</p></div>
                                <a href="{{ $heroVehicle->publicUrl($locale) }}" class="grid size-12 shrink-0 place-items-center rounded-full bg-brand-gold text-brand-deep"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-slate-100 bg-white">
        <div class="mx-auto grid max-w-7xl gap-px bg-slate-200 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['01', $locale === 'en' ? 'European selection' : 'Seleção europeia', $locale === 'en' ? 'Vehicles chosen for quality and value.' : 'Viaturas escolhidas pela qualidade e valor.'],
                ['02', $locale === 'en' ? 'Clear information' : 'Informação transparente', $locale === 'en' ? 'Relevant details, without surprises.' : 'Dados relevantes, sem surpresas.'],
                ['03', $locale === 'en' ? 'Financing support' : 'Apoio ao financiamento', $locale === 'en' ? 'Solutions tailored to your profile.' : 'Soluções ajustadas ao seu perfil.'],
                ['04', $locale === 'en' ? 'Dedicated service' : 'Acompanhamento dedicado', $locale === 'en' ? 'A team present throughout the process.' : 'Uma equipa presente em todo o processo.'],
            ] as [$number, $title, $copy])
                <div class="bg-white px-6 py-8 transition hover:bg-brand-light lg:px-8">
                    <span class="text-xs font-black tracking-[0.2em] text-brand-gold">{{ $number }}</span>
                    <h3 class="mt-3 font-extrabold text-brand-navy">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $copy }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
        <div class="mb-10 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-brand-navy/60">{{ $locale === 'en' ? 'Our collection' : 'A nossa coleção' }}</p>
                <h2 class="mt-3 text-4xl font-black tracking-tight text-brand-deep sm:text-5xl">{{ $locale === 'en' ? 'Selected vehicles' : 'Viaturas selecionadas' }}</h2>
            </div>
            <a href="{{ $locale === 'en' ? route('vehicles.buy.en', ['locale' => 'en']) : route('vehicles.buy.pt', ['locale' => 'pt']) }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-brand-navy transition hover:text-brand-gold">{{ $locale === 'en' ? 'View full collection' : 'Ver coleção completa' }} <span aria-hidden="true">→</span></a>
        </div>
        <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
            @forelse($featuredVehicles as $vehicle)
                <x-vehicle-card :vehicle="$vehicle" :locale="$locale" />
            @empty
                <div class="col-span-full rounded-3xl bg-brand-light p-12 text-center text-slate-600">{{ $locale === 'en' ? 'New vehicles will be available soon.' : 'Novas viaturas estarão disponíveis brevemente.' }}</div>
            @endforelse
        </div>
    </section>

    <section class="bg-brand-light py-20 lg:py-28">
        <div class="mx-auto grid max-w-7xl gap-14 px-4 sm:px-6 lg:grid-cols-[.8fr_1.2fr] lg:px-8">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-brand-navy/60">{{ $locale === 'en' ? 'A simple process' : 'Um processo simples' }}</p>
                <h2 class="mt-3 text-4xl font-black tracking-tight text-brand-deep">{{ $locale === 'en' ? 'From selection to delivery, by your side.' : 'Da escolha à entrega, sempre consigo.' }}</h2>
                <p class="mt-5 leading-7 text-slate-600">{{ $locale === 'en' ? 'We make every stage clear, informed and tailored to what you need.' : 'Tornamos cada etapa clara, informada e ajustada ao que realmente precisa.' }}</p>
            </div>
            <ol class="grid gap-4 sm:grid-cols-2">
                @foreach([
                    [$locale === 'en' ? 'Choose your vehicle' : 'Escolha a viatura', $locale === 'en' ? 'Explore our current selection and compare the essential details.' : 'Explore a seleção atual e compare os dados essenciais.'],
                    [$locale === 'en' ? 'Talk to the team' : 'Fale com a equipa', $locale === 'en' ? 'Clarify questions and receive personalised information.' : 'Esclareça dúvidas e receba informação personalizada.'],
                    [$locale === 'en' ? 'Define the solution' : 'Defina a solução', $locale === 'en' ? 'Purchase, financing or rental tailored to your needs.' : 'Compra, financiamento ou aluguer ajustado às suas necessidades.'],
                    [$locale === 'en' ? 'Supported delivery' : 'Entrega acompanhada', $locale === 'en' ? 'Complete the process with confidence and dedicated support.' : 'Conclua o processo com confiança e apoio dedicado.'],
                ] as $index => [$title, $copy])
                    <li class="rounded-2xl border border-slate-200 bg-white p-6">
                        <span class="grid size-9 place-items-center rounded-full bg-brand-gold text-sm font-black text-brand-deep">{{ $index + 1 }}</span>
                        <h3 class="mt-5 text-lg font-extrabold text-brand-navy">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $copy }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[.75fr_1.25fr] lg:px-8 lg:py-28">
        <div class="self-center">
            <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-brand-navy/60">{{ $locale === 'en' ? 'Personal service' : 'Atendimento personalizado' }}</p>
            <h2 class="mt-3 text-4xl font-black tracking-tight text-brand-deep">{{ $locale === 'en' ? 'Tell us what you are looking for.' : 'Diga-nos o que procura.' }}</h2>
            <p class="mt-5 leading-7 text-slate-600">{{ $locale === 'en' ? 'Share your preferences and our team will contact you with clear, relevant options.' : 'Partilhe as suas preferências e a nossa equipa entrará em contacto com opções claras e relevantes.' }}</p>
            <div class="mt-8 rounded-2xl border border-brand-gold/30 bg-brand-gold/10 p-5 text-sm leading-6 text-brand-deep"><strong>{{ $locale === 'en' ? 'Dedicated response.' : 'Resposta dedicada.' }}</strong> {{ $locale === 'en' ? 'Your request is reviewed directly by our team.' : 'O seu pedido é analisado diretamente pela nossa equipa.' }}</div>
        </div>
        <div class="rounded-[2rem] bg-brand-navy p-6 text-white shadow-[0_28px_80px_-35px_rgba(0,30,74,0.6)] sm:p-9">
            <h2 class="text-2xl font-black">{{ $locale === 'en' ? 'Request contact' : 'Pedido de contacto' }}</h2>
            <livewire:contact-form :locale="$locale" />
        </div>
    </section>

    @if($translation?->content)
        <section class="prose prose-slate mx-auto max-w-4xl px-4 pb-20 sm:px-6">{!! $translation->content !!}</section>
    @endif
</x-front.layouts.app>
