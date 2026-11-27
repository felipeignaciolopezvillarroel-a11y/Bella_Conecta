<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-[#1F1B1A]">Iniciar sesión</h2>
        <p class="text-sm text-gray-600 mt-2">Accede a tu cuenta de Bellas Conecta</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" class="text-sm font-semibold text-[#1F1B1A]" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" :value="__('Contraseña')" class="text-sm font-semibold text-[#1F1B1A]" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-pink-600 hover:text-pink-800 transition-colors" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
            </label>
        </div>

        <x-primary-button class="w-full justify-center bg-pink-600 hover:bg-pink-700 focus:ring-pink-500">
            Iniciar sesión
        </x-primary-button>

        <div class="text-center pt-4 border-t border-[#EEE3DD]">
            <p class="text-sm text-gray-600 mb-6">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-pink-600 hover:text-pink-800 font-semibold transition-colors">
                    Crear cuenta
                </a>
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 mt-2 text-sm text-gray-500 hover:text-pink-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver al inicio
            </a>
        </div>
    </form>
</x-guest-layout>