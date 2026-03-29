@extends('adminlte::page')

@section('title', 'Cargar Multimedia | Software Tech')

@section('content')
<div class="row justify-content-center pt-5">
    <div class="col-md-8">
        <div class="card shadow-lg" style="background: rgba(10, 10, 10, 0.9); border: 1px solid #00d4ff; border-radius: 20px;">
            <div class="card-header border-0 text-center">
                <h3 class="text-white mt-3 font-weight-bold">CARGA DE ACTIVOS A STORAGE</h3>
                <p class="text-muted">DISCO DURO VIRTUAL - SOFTWARE TECH</p>
            </div>
            
            <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                <div class="form-group mb-4">
                    <label class="text-white">ETIQUETA DEL ARCHIVO</label>
                    <input type="text" name="nombre" class="form-control bg-dark border-secondary text-white" placeholder="Ej: Logo Corporativo v2" required>
                </div>

                <div class="form-group mb-4">
                    <label class="text-white">SELECCIONAR RECURSO MULTIMEDIA</label>
                    <div class="custom-file">
                        <input type="file" name="archivo" class="custom-file-input" id="archivo" accept="image/*,video/*,application/pdf" required>
                        <label class="custom-file-label bg-dark border-secondary text-white" for="archivo">Elegir archivo...</label>
                    </div>
                    <small class="text-info">Soporta: Imágenes, Videos (MP4) y Documentos (PDF).</small>
                </div>

                <button type="submit" class="btn btn-gradient-tech-blue btn-block py-3 mt-4">
                    INICIAR CARGA A TERMINAL
                </button>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    // Para que aparezca el nombre del archivo seleccionado en el input de AdminLTE
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@stop