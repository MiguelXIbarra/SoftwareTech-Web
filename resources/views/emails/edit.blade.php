@extends('adminlte::page')
@section('title', 'Editar Correo')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0"
                style="background-color: #0b1120; border-radius: 15px; border-top: 4px solid #ffc107 !important;">
                <div class="card-header border-bottom border-secondary" style="background-color: transparent;">
                    <h3 class="card-title text-bold" style="color: #ffc107;">
                        <i class="fas fa-edit mr-2"></i>MODIFICAR CORREO: {{ strtoupper($emails->subject) }}
                    </h3>
                </div>

                <form action="{{ route('emails.update', $emails->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="form-group mb-4">
                            <label class="small text-uppercase text-bold" style="color: #ffc107;">Asunto del
                                Correo</label>
                            <input type="text" name="subject" class="form-control bg-dark text-white border-secondary"
                                value="{{ old('subject', $emails->subject) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="small text-uppercase text-bold" style="color: #ffc107;">Contenido</label>
                            <textarea name="content" class="form-control bg-dark text-white border-secondary" rows="8"
                                style="font-size: 1.1rem; line-height: 1.6;">{{ old('content', $emails->content) }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer border-top border-secondary text-right"
                        style="background-color: transparent;">
                        <a href="{{ route('emails.index') }}" class="btn btn-outline-secondary px-4 mr-2">CANCELAR</a>
                        <button type="submit" class="btn btn-warning px-5 text-bold shadow" style="color: #000;">
                            <i class="fas fa-save mr-2"></i> ACTUALIZAR CORREO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection