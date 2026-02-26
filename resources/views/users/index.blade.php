@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-bold">Usuarios del Sistema</h1>
    <a href="{{ route('users.create') }}" class="btn text-white shadow-sm" style="background-color: #4472f1;">
        <i class="fas fa-user-plus mr-1"></i> Registrar Usuario
    </a>
</div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Listado General</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="text-center bg-light">
                    <tr>
                        <th style="width: 80px;">Foto</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($users as $user)
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex justify-content-center">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                         class="img-circle elevation-1 border"
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="img-circle bg-secondary d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 45px; height: 45px; margin: 0 auto;">
                                        <i class="fas fa-user text-white-50"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="align-middle text-bold text-dark">{{ $user->name }}</td>
                        <td class="align-middle text-muted">{{ $user->email }}</td>
                        <td class="align-middle">
                            <span class="badge px-3 py-2 text-white shadow-sm" style="background-color: #4472f1; font-size: 0.85rem;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-white border">
                                    <i class="fas fa-eye" style="color: #45a1b5;"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-white border">
                                    <i class="fas fa-edit" style="color: #ffc107;"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-white border" onclick="confirmDelete({{ $user->id }})">
                                        <i class="fas fa-trash-alt" style="color: #e3342f;"></i>
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
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirmación de eliminación (Se queda abierta hasta que decidas)
    function confirmDelete(userId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminarlo',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }

    // Alerta de éxito automática (Aparece al centro y se quita sola)
    @if(session('message'))
        Swal.fire({
            icon: 'success',
            title: '¡Listo!',
            text: "{{ session('message') }}",
            showConfirmButton: false, // Quitamos el botón para que sea automático
            timer: 1200, // Se cierra solo en 1.8 segundos
            timerProgressBar: true, // Línea de tiempo visual en la parte inferior
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    @endif
</script>
@endpush