@props(['variant' => 'dark', 'size' => 'sm'])

@php
    $sizes = [
        'sm' => ['main' => 'text-lg sm:text-xl md:text-2xl', 'tag' => 'hidden sm:block text-[10px] md:text-xs', 'heart' => 'text-base sm:text-lg'],
        'md' => ['main' => 'text-xl md:text-2xl', 'tag' => 'text-[10px] md:text-xs', 'heart' => 'text-lg'],
        'lg' => ['main' => 'text-4xl md:text-5xl', 'tag' => 'text-xs md:text-sm', 'heart' => 'text-3xl'],
    ];
    $s = $sizes[$size] ?? $sizes['sm'];
    $mainColor = $variant === 'light' ? 'text-white' : 'text-[#1F1B1A]';
    $accent    = $variant === 'light' ? 'text-pink-400' : 'text-pink-600';
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col leading-tight']) }}>
    <span class="{{ $s['main'] }} font-light tracking-wide {{ $mainColor }}">
        bellas
        <span class="font-extrabold {{ $accent }} tracking-wider">CONECTA</span>
        <span class="{{ $accent }} {{ $s['heart'] }}">♡</span>
    </span>
    <span class="{{ $s['tag'] }} text-gray-500 tracking-widest uppercase mt-0.5">
        Tu belleza, tu estilo, tu elección
    </span>
</div>