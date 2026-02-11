@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Usuarios del Sistema</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus"></i> Registrar Usuario
        </a>
    </div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Listado General</h3>
    </div>
    <div class="card-body p-0"> {{-- p-0 para que la tabla llegue a los bordes --}}
        @if(session('message'))
            <div class="alert alert-success m-3">{{ session('message') }}</div>
        @endif

        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th class="px-4">Nombre</th>
                    <th>Email</th>
                    <th class="text-center">Rol</th>
                    <th class="text-right px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="align-middle px-4">
                        <span class="text-bold">{{ $user->name }}</span>
                    </td>
                    <td class="align-middle">{{ $user->email }}</td>
                    <td class="align-middle text-center">
                        {{-- Unificamos a un solo color: badge-primary (azul) --}}
                        <span class="badge badge-primary px-3 py-2" style="font-weight: 500; min-width: 90px;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="text-right align-middle px-4">
                        <div class="btn-group">
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-default" title="Ver Perfil">
                                <i class="fas fa-eye text-info"></i>
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-default" title="Editar">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default" title="Borrar" onclick="return confirm('¿Eliminar cuenta de usuario?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection