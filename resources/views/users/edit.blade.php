@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="post">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="role" class="form-control">
                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Cliente</option>
                    <option value="developer" {{ $user->role == 'developer' ? 'selected' : '' }}>Desarrollador</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nueva Contraseña (dejar en blanco para no cambiar)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-warning">Actualizar Usuario</button>
        </form>
    </div>
</div>
@endsection