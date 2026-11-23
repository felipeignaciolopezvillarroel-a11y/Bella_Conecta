<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#1F1B1A] mb-2">¿Olvidaste tu contraseña?</h2>
        <p class="text-sm text-gray-600 leading-relaxed">
            No te preocupes. Ingresa tu correo electrónico y te enviaremos un enlace para que puedas restablecerla y elegir una nueva.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" class="text-sm font-semibold text-[#1F1B1A]" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" placeholder="tu@email.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end pt-2">
            <x-primary-button class="w-full sm:w-auto justify-center bg-pink-600 hover:bg-pink-700 focus:ring-pink-500">
                {{ __('Enviar enlace de restablecimiento') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-4 border-t border-[#EEE3DD]">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-pink-600 transition-colors">
                &larr; Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>