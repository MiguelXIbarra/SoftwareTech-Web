@extends('adminlte::page')

@section('content')
    {{-- 
       Si entras por la ruta de Inertia, se verá esto. 
       Si entras por tu ruta normal, se verá tu perfil azul.
    --}}
    @inertia
@endsection

@push('css')
    @vite(['resources/js/app.js'])
    @inertiaHead
@endpush