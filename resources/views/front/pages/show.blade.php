<x-front.layouts.app :locale="$locale" :title="$translation->meta_title ?: $translation->title" :description="$translation->meta_description">
    <section class="relative overflow-hidden bg-brand-deep py-16 text-white sm:py-20">
        <div class="absolute -right-20 -top-20 size-72 rounded-full border border-brand-gold/20"></div>
        <div class="relative mx-auto max-w-4xl px-4 sm:px-6">
            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-brand-gold">European Car</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">{{ $translation->title }}</h1>
        </div>
    </section>
    <section class="prose prose-slate mx-auto max-w-3xl px-4 py-16 sm:px-6 prose-headings:text-brand-deep prose-a:text-brand-navy">{!! $translation->content !!}</section>
</x-front.layouts.app>
