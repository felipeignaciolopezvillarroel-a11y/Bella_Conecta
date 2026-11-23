@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Resumen General')

@section('content')
<div class="space-y-6">
    
    {{-- Tarjetas de Estadísticas --}}
    @include('admin.partials.stats-cards')

    {{-- Tabla de Últimas Reservas --}}
    @include('admin.partials.recent-bookings')

</div>
@endsection