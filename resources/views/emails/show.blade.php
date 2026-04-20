@extends('adminlte::page')
@section('title', 'Lectura de Correo')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-dark shadow-lg"
                style="background-color: #1a222b; border-radius: 15px; border-top: 4px solid #45a1b5;">
                <div class="card-header" style="background-color: #0b1120;">
                    <h3 class="card-title text-bold" style="color: #45a1b5;">
                        <i class="fas fa-envelope-open-text mr-2"></i> DETALLE DEL CORREO
                    </h3>
                </div>
                <div class="card-body" style="background-color: #1a222b; color: #e0e6ed;">
                    <div class="d-flex align-items-center mb-4 p-3 rounded"
                        style="background-color: rgba(69, 161, 181, 0.1);">
                        <div class="mr-3">
                            <i class="fas fa-user-circle fa-3x" style="color: #45a1b5;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-bold">De: {{ $emails->sender->name ?? 'Sistema Tech' }}</h5>
                            <p class="mb-0 text-info small text-uppercase text-bold">Asunto: {{ $emails->subject }}</p>
                            <small class="text-muted">Fecha: {{ $emails->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>

                    <div class="p-4"
                        style="border-left: 3px solid #45a1b5; background-color: rgba(255,255,255,0.02); font-size: 1.1rem;">
                        <span class="text-muted d-block mb-2 text-uppercase small"
                            style="letter-spacing: 1px;">MENSAJE:</span>
                        <p style="white-space: pre-wrap;">{{ $emails->content }}</p>
                    </div>
                </div>
                <div class="card-footer"
                    style="background-color: #0b1120; border-top: 1px solid rgba(69, 161, 181, 0.2);">
                    <div class="text-center">
                        <a href="{{ route('emails.index') }}" class="btn btn-outline-info px-5"
                            style="border-radius: 20px;">
                            <i class="fas fa-arrow-left mr-2"></i> VOLVER A BANDEJA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection