{{-- TOPBAR --}}
<header class="h-20 bg-white border-b border-[#EEE3DD] flex items-center justify-between px-6 md:px-8 shrink-0">
    
    <div class="flex items-center gap-4">
        {{-- Botón menú (solo móvil) --}}
        <button class="md:hidden text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        
        <h1 class="text-xl font-bold text-[#1F1B1A] hidden sm:block">@yield('page_title', 'Panel de Administración')</h1>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-[#1F1B1A]">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">Administrador</p>
        </div>
        
        {{-- Avatar del usuario (Foto o Inicial) --}}
        <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden">
            @if(auth()->user()->employee && auth()->user()->employee->photo_url)
                <img src="{{ auth()->user()->employee->photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-pink-400 to-fuchsia-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
    </div>
</header>