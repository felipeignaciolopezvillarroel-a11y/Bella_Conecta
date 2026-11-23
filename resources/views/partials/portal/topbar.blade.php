<header class="h-16 bg-white border-b border-[#EEE3DD] flex items-center justify-between px-6 shrink-0">
    <div>
        <h1 class="text-lg font-semibold text-[#1F1B1A]">@yield('page_title', 'Portal del trabajador')</h1>
    </div>

    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">
            {{ auth()->user()->name }}
        </span>
        <a href="{{ route('home') }}" class="text-sm text-pink-600 hover:text-pink-800 transition">
            Ir al sitio
        </a>
    </div>
</header>