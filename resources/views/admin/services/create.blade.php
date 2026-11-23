@extends('layouts.admin')

@section('title', 'Nuevo Servicio')
@section('page_title', 'Crear Nuevo Servicio')

@section('content')
<div class="w-full">
    <div class="mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-sm text-gray-500 hover:text-pink-600">← Volver a la lista</a>
    </div>

    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            @include('admin.services.form', ['service' => null])
            
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.services.index') }}" class="px-6 py-3 rounded-full text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">Cancelar</a>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition shadow-md">
                    Guardar Servicio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection