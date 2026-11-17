<section>
    <header class="flex items-start gap-3 mb-6">
        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-[#1F1B1A]">Actualizar contraseña</h2>
            <p class="mt-1 text-sm text-gray-600">Asegúrate de que tu cuenta use una contraseña larga y segura.</p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                Contraseña actual
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm"
                   autocomplete="current-password">
            @if($errors->updatePassword->get('current_password'))
                <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                Nueva contraseña
            </label>
            <input id="update_password_password" name="password" type="password"
                   class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm"
                   autocomplete="new-password">
            @if($errors->updatePassword->get('password'))
                <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                Confirmar contraseña
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm"
                   autocomplete="new-password">
            @if($errors->updatePassword->get('password_confirmation'))
                <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition shadow-md hover:shadow-pink-500/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Actualizar contraseña
            </button>
        </div>
    </form>
</section>