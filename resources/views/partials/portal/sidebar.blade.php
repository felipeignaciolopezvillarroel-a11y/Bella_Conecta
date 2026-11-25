<aside
    x-data="{ open: false }"
    class="w-64 bg-white border-r border-[#EEE3DD] flex flex-col h-screen shrink-0 hidden md:flex">
    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-[#EEE3DD]">
        <a href="{{ route('portal.dashboard') }}" class="flex flex-col leading-tight">
            <span class="text-lg font-bold text-[#1F1B1A]">Bella Conecta</span>
            <span class="text-xs text-pink-600 font-semibold uppercase tracking-widest">Portal</span>
        </a>
    </div>

    {{-- Navegación --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <a href="{{ route('portal.dashboard') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition
                  {{ request()->routeIs('portal.dashboard') ? 'bg-pink-50 text-pink-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Inicio
        </a>

        {{-- Órdenes de Retiro (solo recepcionistas) --}}
        @if(auth()->user()->employee && auth()->user()->employee->role === 'Recepcionista')
        <a href="{{ route('portal.receptionist.index') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition
                      {{ request()->routeIs('portal.receptionist*') ? 'bg-pink-50 text-pink-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
            Órdenes de Retiro
        </a>
        @endif

        {{-- Aquí puedes añadir más enlaces: Mis reservas, Mi horario, etc. --}}
    </nav>

    {{-- Footer del sidebar --}}
    <div class="border-t border-[#EEE3DD] p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-3 w-full px-4 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>