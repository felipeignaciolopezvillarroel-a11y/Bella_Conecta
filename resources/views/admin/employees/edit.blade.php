@extends('layouts.admin')

@section('title', 'Editar Empleado')
@section('page_title', 'Editar Empleado')

@section('content')
<div class="w-full">
    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="text-sm text-gray-500 hover:text-pink-600">← Volver a la lista</a>
    </div>

    {{-- Grid de 2 columnas --}}
    <div class="grid lg:grid-cols-3 gap-8 items-start">

        {{-- COLUMNA IZQUIERDA: Formulario --}}
        <div class="lg:col-span-2">
            <form action="{{ route('admin.employees.update', $employee->uuid) }}" method="POST" class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')
                @include('admin.employees.form', ['employee' => $employee])

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EEE3DD]">
                    <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition">
                        Cancelar
                    </a>
                    <button type="submit" class="cursor-pointer px-5 py-2.5 text-sm font-semibold text-white bg-pink-600 hover:bg-pink-700 rounded-full transition shadow-sm">
                        Actualizar empleado
                    </button>
                </div>
            </form>
        </div>

        {{-- COLUMNA DERECHA: Guía Explicativa --}}
        <div class="lg:col-span-1 sticky top-24">
            <div class="bg-gradient-to-b from-white to-[#FDF8F5] border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">

                {{-- Header de la Guía --}}
                <div class="bg-pink-50 border-b border-pink-100 p-5">
                    <h3 class="font-bold text-[#1F1B1A] flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        Guía de campos
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Todo lo que necesitas saber para registrar a tu equipo.</p>
                </div>

                {{-- Lista de campos --}}
                <div class="p-5 space-y-5 text-sm">

                    {{-- Nombre --}}
                    <div class="flex gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1F1B1A]">Nombre completo</p>
                            <p class="text-gray-500 mt-0.5 text-xs leading-relaxed">El nombre real de la persona. Si su categoría es "Profesional", aparecerá en la web pública.</p>
                        </div>
                    </div>

                    {{-- Rol --}}
                    <div class="flex gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1F1B1A]">Rol de sistema</p>
                            <ul class="text-gray-500 mt-0.5 text-xs leading-relaxed list-disc list-inside space-y-1">
                                <li><strong>Empleado:</strong> Acceso al portal de trabajadores.</li>
                                <li><strong>Administrador:</strong> Acceso total al panel y configuración.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Especialidad --}}
                    <div class="flex gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1F1B1A]">Especialidad</p>
                            <p class="text-gray-500 mt-0.5 text-xs leading-relaxed">Breve descripción (Ej: Colorista, Peinados). Se mostrará debajo de su nombre en las tarjetas de la web.</p>
                        </div>
                    </div>

                    {{-- Categoría --}}
                    <div class="flex gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-pink-50 flex items-center justify-center text-pink-600">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1F1B1A]">Categoría</p>
                            <ul class="text-gray-500 mt-0.5 text-xs leading-relaxed list-disc list-inside space-y-1">
                                <li><strong>Profesional:</strong> Atiende clientes y aparece en el equipo de la web.</li>
                                <li><strong>Administrativo:</strong> Solo gestión interna, no recibe reservas.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Credenciales --}}
                    <div class="flex gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1F1B1A]">Credenciales</p>
                            <p class="text-gray-500 mt-0.5 text-xs leading-relaxed">El correo y contraseña para ingresar al sistema. <strong class="text-pink-600">Asegúrate de guardarlos y enviárselos.</strong></p>
                        </div>
                    </div>

                    {{-- Perfil público --}}
                    <div class="flex gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1F1B1A]">Biografía y Foto</p>
                            <p class="text-gray-500 mt-0.5 text-xs leading-relaxed">Información e imagen que verán los clientes en su perfil público de la web.</p>
                        </div>
                    </div>
                </div>

                {{-- Tip de Seguridad --}}
                <div class="p-5 border-t border-[#EEE3DD] bg-gray-50">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-[#1F1B1A]">Consejo de seguridad</p>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">La contraseña debe tener al menos 8 caracteres. Recomienda al empleado cambiarla después de su primer inicio de sesión.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection