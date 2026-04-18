@extends('adminlte::page')
@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0"
                style="background-color: #0b1120; border-radius: 15px; border-top: 4px solid #ffc107 !important;">

                <div class="card-header border-bottom border-secondary" style="background-color: transparent;">
                    <h3 class="card-title text-bold" style="color: #ffc107;">
                        <i class="fas fa-user-cog mr-2"></i>PERFIL DE USUARIO: {{ strtoupper($user->name) }}
                    </h3>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="small text-uppercase text-bold" style="color: #ffc107;">Nombre
                                    Completo</label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary"
                                    value="{{ old('name', $user->name) }}"
                                    style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="small text-uppercase text-bold" style="color: #ffc107;">Correo
                                    Electrónico</label>
                                <input type="email" name="email"
                                    class="form-control bg-dark text-white border-secondary"
                                    value="{{ old('email', $user->email) }}"
                                    style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="small text-uppercase text-bold" style="color: #ffc107;">Rol del
                                    Sistema</label>
                                <select name="role" class="form-control bg-dark text-white border-secondary"
                                    style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : ''
                                        }}>Administrador</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : ''
                                        }}>Usuario Estándar</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="small text-uppercase text-bold" style="color: #ffc107;">Nueva Contraseña
                                    (Dejar vacío para no cambiar)</label>
                                <input type="password" name="password"
                                    class="form-control bg-dark text-white border-secondary"
                                    style="border: 1px solid rgba(255, 193, 7, 0.2) !important;">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer border-top border-secondary text-right"
                        style="background-color: transparent;">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4 mr-2"
                            style="border-radius: 20px;">Cancelar</a>
                        <button type="submit" class="btn btn-warning px-5 text-bold shadow"
                            style="background-color: #ffc107; border: none; border-radius: 20px; color: #000;">
                            <i class="fas fa-sync mr-2"></i> ACTUALIZAR PERFIL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection