<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-[#1F1B1A]">Iniciar sesión</h2>
        <p class="text-sm text-gray-600 mt-1">Accede a tu cuenta de Bella Conecta</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-pink-600 hover:text-pink-800 underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <x-primary-button>
            Iniciar sesión
        </x-primary-button>

        <p class="text-center text-sm text-gray-600 pt-2">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-pink-600 hover:text-pink-800 font-semibold">
                Crear cuenta
            </a>
        </p>
    </form>
</x-guest-layout>