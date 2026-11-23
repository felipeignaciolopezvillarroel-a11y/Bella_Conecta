<section>
    <header class="flex items-start gap-3 mb-6">
        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-[#1F1B1A]">Información del perfil</h2>
            <p class="mt-1 text-sm text-gray-600">Actualiza la información de tu cuenta y tu correo electrónico.</p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                Nombre completo
            </label>
            <input id="name" name="name" type="text"
                   class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm"
                   value="{{ old('name', $user->name) }}"
                   required autofocus autocomplete="name">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                Correo electrónico
            </label>
            <input id="email" name="email" type="email"
                   class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm"
                   value="{{ old('email', $user->email) }}"
                   required autocomplete="username">
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <p class="text-sm text-yellow-800">
                        Tu correo electrónico no está verificado.
                        <button form="send-verification" class="cursor-pointer underline text-yellow-900 hover:text-yellow-700 font-semibold">
                            Haz click aquí para reenviar el correo de verificación.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-700 font-medium">
                            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="cursor-pointer inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition shadow-md hover:shadow-pink-500/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
        </div>
    </form>
</section>