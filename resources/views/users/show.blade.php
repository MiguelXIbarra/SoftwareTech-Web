@extends('adminlte::page')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        {{-- Color exacto del icono OJO (#45a1b5) --}}
        <div class="card card-outline shadow" style="border-top: 3px solid #45a1b5;">
            <div class="card-header" style="background-color: #45a1b5;">
                <h3 class="card-title text-bold text-white">Visualizar Perfil de Usuario</h3>
            </div>
            
            <div class="card-body">
                <div class="text-center mb-4">
                    {{-- Borde de la foto sincronizado --}}
                    <div class="d-inline-block shadow-sm" style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; border: 4px solid #45a1b5; background: #f4f6f9;">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user fa-7x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="mt-3 text-bold">{{ $user->name }}</h3>
                    <span class="badge px-3 py-2 text-white" style="background-color: #45a1b5;">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <hr>

                <div class="row px-4">
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted small d-block">CORREO ELECTRÓNICO</label>
                        <p class="h6"><i class="fas fa-envelope mr-2" style="color: #45a1b5;"></i> {{ $user->email }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted small d-block">ESTADO DE CUENTA</label>
                        <p class="h6"><i class="fas fa-check-circle mr-2" style="color: #45a1b5;"></i> Activo</p>
                    </div>
                </div>

                <div class="text-center mt-4">
                    {{-- Botón ahora también en el color turquesa del icono --}}
                    <a href="{{ route('users.edit', $user->id) }}" class="btn text-white px-4 shadow-sm" style="background-color: #45a1b5;">
                        <i class="fas fa-edit mr-1"></i> Ir a Editar
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-default px-4 ml-2 border">
                        Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection