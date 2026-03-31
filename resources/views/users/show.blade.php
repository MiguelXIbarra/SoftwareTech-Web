@extends('adminlte::page')
@section('title', 'Perfil de Usuario')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-dark shadow-lg"
                style="background-color: #1a222b; border-radius: 15px; border: 1px solid #45a1b5;">
                <div class="card-header text-center"
                    style="background-color: #0b1120; border-bottom: 2px solid #45a1b5;">
                    <h3 class="card-title text-bold w-100" style="color: #45a1b5;">DETALLES DEL USUARIO</h3>
                </div>
                <div class="card-body text-center" style="color: #e0e6ed;">
                    <i class="fas fa-user-circle fa-7x mb-4" style="color: #45a1b5;"></i>
                    <h2 class="text-bold">{{ $user->name }}</h2>
                    <p class="text-muted mb-4">{{ $user->email }}</p>

                    <div class="row text-left mt-4 p-3 rounded"
                        style="background-color: rgba(69, 161, 181, 0.05); border: 1px solid rgba(69, 161, 181, 0.2);">
                        <div class="col-md-6">
                            <strong><i class="fas fa-id-card mr-2"></i> ID de Empleado:</strong>
                            <p class="text-info">{{ $user->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-check mr-2"></i> Miembro desde:</strong>
                            <p class="text-info">{{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="background-color: #0b1120;">
                    <div class="text-center">
                        <a href="{{ route('users.index') }}"
                            class="btn btn-outline-info px-4 border-radius-20">Regresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection