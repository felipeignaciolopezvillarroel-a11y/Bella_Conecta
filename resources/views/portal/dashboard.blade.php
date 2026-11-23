@extends('layouts.portal')

@section('title', 'Mi portal')
@section('page_title', 'Mi portal')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-[#1F1B1A] mb-2">
        Hola, {{ auth()->user()->name }}
    </h1>
    <p class="text-gray-600 mb-8">
        Bienvenido a tu portal de trabajador.
    </p>

    <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 shadow-sm">
        <p class="text-sm text-gray-600">
            Aquí podrás ver tus reservas, horarios y más.
        </p>
    </div>
</div>
@endsection