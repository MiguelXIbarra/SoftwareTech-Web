@extends('adminlte::page')

@section('title', 'Perfil | Software Tech')

@section('content_header')
    <h1 class="m-0 dna-gradient" style="background: linear-gradient(135deg, #007bff, #8a2be2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800;">PERFIL DE OPERADOR</h1>
@stop

@section('css')
<style>
    .card, .card-header, .card-footer, .list-group-item {
        background-color: transparent !important;
        border: none !important;
    }
    .card {
        background: rgba(255, 255, 255, 0.01) !important;
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid rgba(138, 43, 226, 0.3) !important;
        border-radius: 20px !important;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.8) !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #8a2be2 0%, #007bff 100%) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }
    .btn-primary:hover {
        transform: translateY(-3px) scale(1.05) !important;
        box-shadow: 0 0 30px rgba(0, 123, 255, 0.7) !important;
    }
    .profile-user-img {
        border: 3px solid #8a2be2 !important;
        box-shadow: 0 0 20px rgba(138, 43, 226, 0.5);
    }
</style>
@stop

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <div class="mb-3">
                    <img class="profile-user-img img-fluid img-circle"
                         src="https://ui-avatars.com/api/?name={{ str_replace(' ', '+', Auth::user()->name) }}&color=7F9CF5&background=1a1a1a"
                         alt="User profile picture">
                </div>
                <h3 class="text-white fw-bold mb-0">{{ Auth::user()->name }}</h3>
                <p class="text-muted mb-4">OPERADOR CORE</p>
                <a href="{{ route('settings') }}" class="btn btn-primary btn-block">GESTIONAR CUENTA</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4">
                <div class="card-header"><h3 class="text-white fw-bold">ESPECIFICACIONES DEL OPERADOR</h3></div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-4 text-info fw-bold">NOMBRE COMPLETO</div>
                        <div class="col-sm-8 text-white">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-4 text-info fw-bold">CORREO ELECTRÓNICO</div>
                        <div class="col-sm-8 text-white">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop