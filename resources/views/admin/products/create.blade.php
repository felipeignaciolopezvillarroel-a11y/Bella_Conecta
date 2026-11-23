@extends('layouts.admin')

@section('title', 'Nuevo Producto')
@section('page_title', 'Nuevo Producto')

@section('content')
<div class="w-full" 
     x-data="{ 
        name: '{{ old('name') }}',
        priceRaw: '{{ old('price') }}',
        discountRaw: '{{ old('discount_price') }}',
        image: '',
        
        formatNumber(value) {
            let number = value.toString().replace(/\D/g, '');
            return number ? parseInt(number).toLocaleString('es-CL') : '';
        },
        
        updateImage(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.image = e.target.result; };
                reader.readAsDataURL(file);
            }
        }
     }">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-pink-600">← Volver a la lista</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8 items-start">

        {{-- Formulario --}}
        <div class="lg:col-span-2">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
                @csrf
                
                {{-- Aquí va tu form.blade.php pero con x-model --}}
                @include('admin.products.form', ['product' => null])

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EEE3DD]">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition">
                        Cancelar
                    </a>
                    <button type="submit" class="cursor-pointer px-5 py-2.5 text-sm font-semibold text-white bg-pink-600 hover:bg-pink-700 rounded-full transition shadow-sm">
                        Guardar producto
                    </button>
                </div>
            </form>
        </div>

        {{-- Vista previa del producto --}}
        <div class="lg:col-span-1 sticky top-24 space-y-6">

            {{-- Vista Previa del Producto --}}
            <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm p-5">
                <h4 class="text-sm font-bold text-[#1F1B1A] mb-4">Vista previa en la web</h4>

                <div class="w-full">
                    <div class="group bg-white rounded-2xl shadow-md border border-[#EEE3DD] overflow-hidden flex flex-col h-full">
                        <div class="block relative bg-gradient-to-br from-pink-50 to-fuchsia-50 aspect-square flex items-center justify-center overflow-hidden">
                            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-pink-200/50 blur-2xl"></div>
                            <div class="absolute -bottom-8 -left-8 w-24 h-24 rounded-full bg-fuchsia-200/50 blur-2xl"></div>

                            <template x-if="image">
                                <img :src="image" class="relative w-full h-full object-cover">
                            </template>
                            <template x-if="!image">
                                <span class="relative text-7xl sm:text-8xl text-gray-300">🧴</span>
                            </template>
                        </div>

                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="text-[#1F1B1A] font-semibold text-base tracking-tight leading-tight mb-2 line-clamp-2" x-text="name || 'Nombre del producto'"></h3>

                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                </div>
                            </div>

                            <div class="mt-auto flex items-center justify-between gap-2">
                                <div class="flex flex-col">
                                    <span class="text-lg font-bold text-[#1F1B1A]"
                                        x-text="'$' + (discountRaw ? parseInt(discountRaw).toLocaleString('es-CL') : parseInt(priceRaw || 0).toLocaleString('es-CL'))"></span>
                                    <template x-if="discountRaw && parseInt(discountRaw) < parseInt(priceRaw)">
                                        <span class="text-xs text-gray-400 line-through" x-text="'$' + parseInt(priceRaw || 0).toLocaleString('es-CL')"></span>
                                    </template>
                                </div>
                                <button type="button" class="bg-pink-600 text-white font-medium rounded-full text-xs px-3 py-2 shadow-md">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mensaje de ayuda / Duda --}}
            <div class="bg-pink-50 border border-pink-200 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1F1B1A] text-sm">¿Tienes dudas sobre algún campo?</p>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                            Si no estás seguro de qué ingresar en algún campo, puedes consultar la guía de campos más adelante o contactar al administrador del sistema.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection