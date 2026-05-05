<!doctype html>
<html lang="{{ $locale }}">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Social preview</title>@vite(['resources/css/app.css'])</head>
<body class="bg-slate-200 p-8">
@php($size = $format === 'story' ? 'h-[1920px] w-[1080px]' : 'h-[1080px] w-[1080px]')
<div class="{{ $size }} mx-auto overflow-hidden bg-[#001E4A] text-white shadow-2xl">
    <div class="relative h-2/3">
        @if($vehicle->mainImageUrl())
            <img src="{{ $vehicle->mainImageUrl() }}" class="h-full w-full object-cover" alt="">
        @endif
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#001E4A] to-transparent p-12">
            <p class="text-4xl font-black text-[#F7B500]">{{ $settings->site_name }}</p>
        </div>
    </div>
    <div class="p-12">
        <h1 class="text-6xl font-black leading-tight">{{ $vehicle->publicTitle($locale) }}</h1>
        <p class="mt-6 text-3xl">{{ $vehicle->year }} · {{ number_format((int) $vehicle->mileage, 0, ',', ' ') }} km · {{ $vehicle->fuel_type }} · {{ $vehicle->transmission }}</p>
        <p class="mt-8 text-6xl font-black text-[#F7B500]">{{ $vehicle->price_on_request ? 'Sob consulta' : number_format((float) $vehicle->sale_price, 0, ',', ' ').'€' }}</p>
        <p class="mt-8 whitespace-pre-line text-2xl">{{ $copy }}</p>
    </div>
</div>
</body>
</html>
