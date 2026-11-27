@extends('layouts.main')

@section('title', 'Mi cuenta — Bellas Conecta')

@section('content')
<section class="py-12 sm:py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full mx-auto px-4 sm:px-8 lg:px-12">

        <div class="mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-pink-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al inicio
            </a>
            <h1 class="text-3xl sm:text-4xl font-bold text-[#1F1B1A] mt-3">Mi cuenta</h1>
            <p class="text-gray-600 mt-2">Administra tu información personal y seguridad.</p>
        </div>

        <div class="grid lg:grid-cols-4 gap-6 lg:gap-8 items-start">

            {{-- COLUMNA IZQUIERDA: menú rápido --}}
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-pink-400 to-fuchsia-500 flex items-center justify-center text-white font-bold text-xl shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-[#1F1B1A] truncate">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="border-t border-[#EEE3DD] pt-4 space-y-1">
                        <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Mis reservas</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-pink-600 font-semibold bg-pink-50">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Mi cuenta</span>
                        </a>

                        @if(auth()->user()->isStaff())
                            <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Portal trabajadores</span>
                            </a>
                        @endif

                        @if(auth()->user()->isAdmin() && Route::has('admin.dashboard'))
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Panel administrativo</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Info adicional --}}
                <div class="bg-pink-50 border border-pink-200 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1F1B1A] text-sm">Tu cuenta es tuya</p>
                            <p class="text-xs text-gray-600 mt-1">
                                Puedes actualizar tus datos o eliminar tu cuenta cuando quieras.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: formularios --}}
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-white border border-red-200 rounded-2xl p-6 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</section>
@endsection