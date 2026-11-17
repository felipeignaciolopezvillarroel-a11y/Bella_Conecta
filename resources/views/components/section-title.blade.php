@props(['title', 'subtitle' => null])

<div class="text-center mb-10 sm:mb-14">
    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3">{{ $title }}</h2>
    @if ($subtitle)
        <p class="text-gray-600 max-w-xl mx-auto text-sm sm:text-base">{{ $subtitle }}</p>
    @endif
</div>