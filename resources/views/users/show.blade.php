@extends('adminlte::page')

@section('content')
<div class="card card-widget widget-user-2">
    <div class="widget-user-header bg-warning">
        <h3 class="widget-user-username">{{ $user->name }}</h3>
        <h5 class="widget-user-desc">Rol: {{ ucfirst($user->role) }}</h5>
    </div>
    <div class="card-footer p-0">
        <ul class="nav flex-column">
            <li class="nav-item">
                <span class="nav-link">Email: <span class="float-right">{{ $user->email }}</span></span>
            </li>
            <li class="nav-item">
                <span class="nav-link">Miembro desde: <span class="float-right">{{ $user->created_at->format('d M, Y') }}</span></span>
            </li>
        </ul>
    </div>
    <div class="card-footer">
        <a href="{{ route('users.index') }}" class="btn btn-default">Regresar</a>
    </div>
</div>
@endsection