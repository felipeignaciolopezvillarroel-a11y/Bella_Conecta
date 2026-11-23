@props(['title', 'value', 'icon', 'color' => 'gray', 'subtitle' => null])

@php
    $colors = [
        'gray' => 'text-gray-500',
        'pink' => 'text-pink-600',
        'green' => 'text-green-600',
    ];
    $textColor = $colors[$color] ?? $colors['gray'];
@endphp

<div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between mb-3">
        <span class="text-sm font-semibold text-gray-500">{{ $title }}</span>
        <div class="text-2xl">{{ $icon }}</div>
    </div>
    
    <p class="text-3xl font-bold text-[#1F1B1A] {{ $textColor }}">{{ $value }}</p>
    
    @if($subtitle)
        <p class="text-xs text-gray-500 mt-2">{{ $subtitle }}</p>
    @endif
</div>