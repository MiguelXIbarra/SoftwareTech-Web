@extends('adminlte::page')
@section('title', 'Editar Mensaje')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0"
                style="background-color: #0b1120; border-radius: 15px; border-top: 4px solid #ffc107 !important;">

                <div class="card-header border-bottom border-secondary" style="background-color: transparent;">
                    <h3 class="card-title text-bold" style="color: #ffc107;">
                        <i class="fas fa-envelope-open-text mr-2"></i>MODIFICAR COMUNICACIÓN: {{
                        strtoupper($message->title) }}
                    </h3>
                </div>

                <form action="{{ route('messages.update', $message->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="form-group mb-4">
                            <label class="small text-uppercase text-bold" style="color: #ffc107;">Asunto</label>
                            <input type="text" name="title" class="form-control bg-dark text-white border-secondary"
                                value="{{ old('title', $message->title) }}"
                                style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                        </div>

                        <div class="form-group">
                            <label class="small text-uppercase text-bold" style="color: #ffc107;">Cuerpo del
                                Mensaje</label>
                            <textarea name="content" class="form-control textarea-autosize"
                                style="background-color: rgba(255, 193, 7, 0.03); color: #e0e6ed; border: 1px solid rgba(255, 193, 7, 0.1) !important; font-size: 1.1rem; line-height: 1.6; padding: 10px 0;">{{ old('content', $message->content) }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer border-top border-secondary text-right"
                        style="background-color: transparent;">
                        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary px-4 mr-2"
                            style="border-radius: 20px;">Descartar</a>
                        <button type="submit" class="btn btn-warning px-5 text-bold shadow"
                            style="background-color: #ffc107; border: none; border-radius: 20px; color: #000;">
                            <i class="fas fa-save mr-2"></i> ACTUALIZAR MENSAJE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection