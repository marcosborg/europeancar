<x-front.layouts.app :locale="$locale" :title="$translation->meta_title ?: $translation->title" :description="$translation->meta_description">
    <section class="bg-[#F5F7FA] py-12">
        <div class="mx-auto max-w-4xl px-4">
            <h1 class="text-4xl font-black text-[#002B6B]">{{ $translation->title }}</h1>
        </div>
    </section>
    <section class="mx-auto max-w-4xl px-4 py-12 prose prose-slate">{!! $translation->content !!}</section>
</x-front.layouts.app>
