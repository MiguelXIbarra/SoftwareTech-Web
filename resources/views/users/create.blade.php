@extends('adminlte::page')

@section('title', 'Registrar Usuario')

@section('content')
<div class="container">
    <div class="row">
        <h2>Crear Nuevo Usuario</h2>
    </div>
    <div class="row">
        <form action="{{ route('users.store') }}" method="post" class="col-lg-7">
            @csrf
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label>Rol de Usuario</label>
                <select class="form-control" name="role">
                    <option value="client">Cliente</option>
                    <option value="developer">Desarrollador</option>
                    <option value="admin">Administrador</option>
                    <option value="superadmin">Super Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar Usuario</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection