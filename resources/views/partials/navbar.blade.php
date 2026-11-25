<header x-data="{ open: false, userMenu: false }" class="w-full border-b border-[#EEE3DD] bg-white/90 backdrop-blur sticky top-0 z-50">
    <nav class="w-full max-w-screen-2xl mx-auto flex items-center justify-between px-4 sm:px-8 py-4">

        <a href="{{ route('home') }}" class="flex flex-col leading-tight shrink-0">
            <x-logo size="sm" />
        </a>

        <ul class="hidden lg:flex items-center gap-6 xl:gap-8 text-sm font-medium text-gray-700">
            <li>
                <a href="{{ route('home') }}"
                    class="transition {{ request()->routeIs('home') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Inicio
                </a>
            </li>
            <li>
                <a href="{{ route('services.index') }}"
                    class="transition {{ request()->routeIs('services.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Servicios
                </a>
            </li>
            <li>
                <a href="{{ route('team.index') }}"
                    class="transition {{ request()->routeIs('team.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Nuestro Equipo
                </a>
            </li>
            <li>
                <a href="{{ route('bookings.pick') }}"
                    class="transition {{ request()->routeIs('bookings.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Reservar
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}"
                    class="transition {{ request()->routeIs('products.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Productos
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}#reseñas"
                    class="hover:text-pink-600 transition">
                    Reseñas
                </a>
            </li>
        </ul>

        {{-- BOTONES AUTH DESKTOP --}}
        <div class="hidden lg:flex items-center gap-3">
            @auth
            {{-- ========== DROPDOWN PARA CUALQUIER USUARIO AUTENTICADO ========== --}}
            <div class="relative" @click.away="userMenu = false">
                <button @click="userMenu = !userMenu"
                    class="cursor-pointer flex items-center gap-2 text-sm font-medium transition {{ request()->routeIs('profile.*', 'portal.*', 'admin.*') ? 'text-pink-600 font-semibold' : 'text-gray-700 hover:text-pink-600' }}">
                    <span>{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 transition-transform" :class="userMenu ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="userMenu"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    class="absolute right-0 mt-4 w-64 bg-white border border-[#EEE3DD] rounded-xl shadow-xl overflow-hidden z-50">

                    {{-- Navegación --}}
                    <div class="p-2">
                        <nav class="grid gap-1">

                            {{-- Mi cuenta: todos los usuarios --}}
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 py-2.5 px-3 text-sm rounded-lg transition {{ request()->routeIs('profile.*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                <span>Mi cuenta</span>
                            </a>

                            {{-- Portal trabajadores: solo empleados y admins --}}
                            @if(auth()->user()->isStaff())
                            <a href="{{ route('portal.dashboard') }}"
                                class="flex items-center gap-3 py-2.5 px-3 text-sm rounded-lg transition {{ request()->routeIs('portal.*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                </svg>
                                <span>Portal trabajadores</span>
                            </a>
                            @endif

                            {{-- Panel administrativo: solo admins --}}
                            @if(auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center gap-3 py-2.5 px-3 text-sm rounded-lg transition {{ request()->routeIs('admin.*') ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-700 hover:bg-pink-50 hover:text-pink-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M9 17h6" />
                                    <path d="M9 13h6" />
                                </svg>
                                <span>Panel administrativo</span>
                            </a>
                            @endif

                        </nav>
                    </div>

                    {{-- Logout --}}
                    <div class="border-t border-[#EEE3DD] p-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="cursor-pointer flex items-center gap-3 w-full py-2.5 px-3 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M9 12h12l-3 -3" />
                                    <path d="M18 15l3 -3" />
                                </svg>
                                <span>Cerrar sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}"
                class="text-sm font-medium transition {{ request()->routeIs('login') ? 'text-pink-600 font-semibold' : 'text-gray-700 hover:text-pink-600' }}">
                Iniciar sesión
            </a>
            <a href="{{ route('register') }}"
                class="text-white text-sm font-semibold px-5 py-2.5 rounded-full transition shadow-sm {{ request()->routeIs('register') ? 'bg-pink-700 ring-2 ring-pink-200' : 'bg-pink-600 hover:bg-pink-700' }}">
                Crear cuenta
            </a>
            @endauth
        </div>

        <button @click="open = !open" class="lg:hidden p-2 rounded-md text-gray-700 hover:bg-pink-50 transition" aria-label="Menú">
            <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </nav>

    {{-- MENÚ MOBILE --}}
    <div x-show="open" x-cloak x-transition class="lg:hidden border-t border-[#EEE3DD] bg-white">
        <ul class="px-6 py-4 space-y-3 text-sm font-medium text-gray-700">
            <li>
                <a href="{{ route('home') }}" @click="open = false"
                    class="block py-1 transition {{ request()->routeIs('home') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Inicio
                </a>
            </li>
            <li>
                <a href="{{ route('services.index') }}" @click="open = false"
                    class="block py-1 transition {{ request()->routeIs('services.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Servicios
                </a>
            </li>
            <li>
                <a href="{{ route('team.index') }}" @click="open = false"
                    class="block py-1 transition {{ request()->routeIs('team.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Estilistas
                </a>
            </li>
            <li>
                <a href="{{ route('bookings.pick') }}" @click="open = false"
                    class="block py-1 transition {{ request()->routeIs('bookings.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Reservar
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}" @click="open = false"
                    class="block py-1 transition {{ request()->routeIs('products.*') ? 'text-pink-600 font-semibold' : 'hover:text-pink-600' }}">
                    Productos
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}#reseñas" @click="open = false"
                    class="block py-1 hover:text-pink-600">
                    Reseñas
                </a>
            </li>
        </ul>

        {{-- BOTONES AUTH MOBILE --}}
        <div class="px-6 pb-6 flex flex-col gap-3 border-t border-[#EEE3DD] pt-4">
            @auth
            {{-- Mostrar nombre de usuario --}}
            <div class="text-center mb-2">
                <p class="text-xs text-gray-500">Sesión iniciada como</p>
                <p class="text-sm font-semibold text-[#1F1B1A]">{{ auth()->user()->name }}</p>
            </div>

            {{-- Mi cuenta: todos --}}
            <a href="{{ route('profile.edit') }}" @click="open = false"
                class="w-full text-center text-sm font-medium rounded-full py-2.5 transition {{ request()->routeIs('profile.*') ? 'bg-pink-50 border border-pink-600 text-pink-600 font-semibold' : 'text-gray-700 border border-gray-300 hover:border-pink-600 hover:text-pink-600' }}">
                👤 Mi cuenta
            </a>

            {{-- Portal trabajadores: empleados y admins --}}
            @if(auth()->user()->isStaff())
            <a href="{{ route('portal.dashboard') }}" @click="open = false"
                class="w-full text-center text-sm font-medium rounded-full py-2.5 transition {{ request()->routeIs('portal.*') ? 'bg-pink-50 border border-pink-600 text-pink-600 font-semibold' : 'text-gray-700 border border-gray-300 hover:border-pink-600 hover:text-pink-600' }}">
                ⚙️ Portal trabajadores
            </a>
            @endif

            {{-- Panel administrativo: solo admins --}}
            @if(auth()->user()->isAdmin() && Route::has('admin.dashboard'))
            <a href="{{ route('admin.dashboard') }}" @click="open = false"
                class="w-full text-center text-sm font-medium rounded-full py-2.5 transition {{ request()->routeIs('admin.*') ? 'bg-pink-50 border border-pink-600 text-pink-600 font-semibold' : 'text-gray-700 border border-gray-300 hover:border-pink-600 hover:text-pink-600' }}">
                📊 Panel administrativo
            </a>
            @endif

            {{-- Logout: todos --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition shadow-sm">
                    Cerrar sesión
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" @click="open = false"
                class="w-full text-center text-sm font-medium rounded-full py-2.5 transition {{ request()->routeIs('login') ? 'bg-pink-50 border border-pink-600 text-pink-600 font-semibold' : 'text-gray-700 border border-gray-300 hover:border-pink-600 hover:text-pink-600' }}">
                Iniciar sesión
            </a>
            <a href="{{ route('register') }}" @click="open = false"
                class="w-full text-center text-white text-sm font-semibold px-5 py-2.5 rounded-full transition shadow-sm {{ request()->routeIs('register') ? 'bg-pink-700 ring-2 ring-pink-200' : 'bg-pink-600 hover:bg-pink-700' }}">
                Crear cuenta
            </a>
            @endauth
        </div>
    </div>
</header>