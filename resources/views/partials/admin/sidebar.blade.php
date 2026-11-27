{{-- SIDEBAR COLAPSABLE --}}
<aside x-data="{ open: true }"
    :class="open ? 'w-72' : 'w-20'"
    class="bg-white border-r border-gray-200 flex flex-col h-screen shrink-0 transition-all duration-300 ease-in-out relative hidden md:flex z-20">

    {{-- Header (Solo el Logo) --}}
    <div class="h-20 flex items-center px-6 border-b border-gray-100 shrink-0 relative">

        {{-- Logo Abierto --}}
        <a href="{{ route('admin.dashboard') }}" x-show="open" x-transition.opacity class="flex flex-col shrink-0">
            <x-logo size="sm" />
        </a>

        {{-- Logo Cerrado (Solo la "b") --}}
        <a href="{{ route('admin.dashboard') }}" x-show="!open" x-transition.opacity class="mx-auto text-2xl font-light text-[#1F1B1A]">
            b<span class="font-extrabold text-pink-600">.</span>
        </a>

        {{-- Botón Flecha Flotante --}}
        <button @click="open = !open"
            class="absolute top-1/2 -translate-y-1/2 w-7 h-7 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-pink-600 hover:border-pink-300 transition shadow-sm z-50 cursor-pointer"
            style="right: -14px;">
            <svg class="w-3 h-3 transition-transform duration-300" :class="open ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>

    {{-- Tarjeta de Perfil (Oculta al colapsar) --}}
    <div class="px-3 py-4" x-show="open" x-transition.opacity>
        <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-100 transition overflow-hidden whitespace-nowrap">
            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-[#1F1B1A] truncate">Bellas Conecta</p>
                <p class="text-[11px] text-gray-500 truncate">Santiago, Chile</p>
            </div>
        </div>
    </div>

    {{-- Navegación (Con scroll si es necesario) --}}
    <nav class="flex-1 overflow-y-auto py-2 px-3 space-y-6 scrollbar-hide">

        {{-- GENERAL --}}
        <div>
            <p x-show="open" x-transition.opacity class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 whitespace-nowrap">General</p>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}" title="Dashboard">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-pink-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
                </a>
            </div>
        </div>

        {{-- GESTIÓN --}}
        <div>
            <p x-show="open" x-transition.opacity class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 whitespace-nowrap">Gestión</p>
            <div class="space-y-1">
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.bookings*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}" title="Reservas">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.bookings*') ? 'text-pink-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Reservas</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.services*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}" title="Servicios">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.services*') ? 'text-pink-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Servicios</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.products*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}" title="Productos">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.products*') ? 'text-pink-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Productos</span>
                </a>
                <a href="{{ route('admin.employees.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.employees.*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.employees.*') ? 'text-pink-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Empleados</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-pink-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Usuarios</span>
                </a>
            </div>
        </div>

        {{-- OPERACIONES --}}
        <div>
            <p x-show="open" x-transition.opacity class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 whitespace-nowrap">Operaciones</p>
            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-xl transition text-gray-600 hover:bg-gray-50 hover:text-gray-900" title="Historial de Cambios">
                    <svg class="w-6 h-6 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Historial</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-xl transition text-gray-600 hover:bg-gray-50 hover:text-gray-900" title="Deshacer Cambios">
                    <svg class="w-6 h-6 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Deshacer</span>
                </a>
            </div>
        </div>

        {{-- COMUNICACIÓN --}}
        <div>
            <p x-show="open" x-transition.opacity class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 whitespace-nowrap">Comunicación</p>
            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-xl transition text-gray-600 hover:bg-gray-50 hover:text-gray-900 relative" title="Chat en Vivo">
                    <svg class="w-6 h-6 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                    </svg>
                    <span x-show="open" x-transition.opacity class="whitespace-nowrap">Chat en Vivo</span>
                    {{-- Puntito rojo de notificación --}}
                    <span x-show="open" class="absolute right-4 top-1/2 -translate-y-1/2 w-2 h-2 bg-red-500 rounded-full"></span>
                </a>
            </div>
        </div>

    </nav>

    {{-- Footer Sidebar --}}
    <div class="p-3 border-t border-gray-100 space-y-1">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-500 hover:text-gray-900 transition" title="Volver al sitio">
            <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
            </svg>
            <span x-show="open" x-transition.opacity class="whitespace-nowrap">Volver al sitio</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 text-sm text-red-500 hover:text-red-700 transition" title="Cerrar sesión">
                <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                <span x-show="open" x-transition.opacity class="whitespace-nowrap">Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>