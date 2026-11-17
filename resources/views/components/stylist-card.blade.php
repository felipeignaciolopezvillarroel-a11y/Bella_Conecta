@props(['name', 'role', 'image'])

<div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
    <img src="{{ $image }}" alt="{{ $name }}" class="w-full aspect-square object-cover" />
    <div class="p-6 text-center">
        <h3 class="font-semibold text-lg">{{ $name }}</h3>
        <p class="text-sm text-pink-600 mt-1">{{ $role }}</p>
    </div>
</div>