@props([
    'name' => 'confirm',
    'title' => 'Confirmar acción',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'variant' => 'danger', // danger | warning | info
])

@php
    $variants = [
        'danger'  => ['btn' => 'bg-red-600 hover:bg-red-700'],
        'warning' => ['btn' => 'bg-amber-600 hover:bg-amber-700'],
        'info'    => ['btn' => 'bg-blue-600 hover:bg-blue-700'],
    ];
    $v = $variants[$variant] ?? $variants['danger'];
@endphp

<div
    x-data="{ open: false, payload: {} }"
    x-on:open-confirm-{{ $name }}.window="open = true; payload = $event.detail ?? {}"
    x-on:close-confirm-{{ $name }}.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        x-on:click="open = false"
    ></div>

    {{-- Card --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 sm:p-7 text-left"
        x-on:keydown.escape.window="open = false"
    >
        {{-- Título --}}
        <h3 class="text-lg font-bold text-[#1F1B1A] text-left">
            {{ $title }}
        </h3>

        {{-- Contenido (slot) — el padre pasa los datos específicos --}}
        <div class="mt-3 text-sm text-gray-600 text-left space-y-2">
            {{ $slot }}
        </div>

        {{-- Acciones --}}
        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
            <button
                type="button"
                x-on:click="open = false"
                class="cursor-pointer inline-flex justify-center items-center px-4 py-2 rounded-full text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition"
            >
                {{ $cancelText }}
            </button>

            <button
                type="button"
                x-on:click="
                    open = false;
                    $dispatch('confirmed-{{ $name }}', payload);
                "
                class="cursor-pointer inline-flex justify-center items-center px-4 py-2 rounded-full text-sm font-semibold text-white {{ $v['btn'] }} transition shadow-sm"
            >
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>