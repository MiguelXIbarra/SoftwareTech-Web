@extends('adminlte::page')

@section('title', 'Nuevo Usuario')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .img-container { max-height: 400px; margin-bottom: 10px; }
    .img-container img { max-width: 100%; }
    .cropper-view-box, .cropper-face { border-radius: 50%; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-outline shadow" style="border-top: 3px solid #4472f1;">
            <div class="card-header" style="background-color: #4472f1;">
                <h3 class="card-title text-bold text-white">Registrar Nuevo Usuario</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post" id="create-user-form">
                    @csrf
                    
                    <div class="form-group text-center">
                        <label class="d-block mb-3 text-muted small text-bold">FOTO DE PERFIL</label>
                        <div class="position-relative d-inline-block">
                            <div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; border: 4px solid #4472f1; background: #f4f6f9; display: flex; align-items: center; justify-content: center;">
                                <i id="placeholder-icon" class="fas fa-user-plus fa-6x text-muted"></i>
                                <img id="preview-image" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            </div>

                            <label for="profile_photo_input" class="btn text-white position-absolute shadow" 
                                   style="background-color: #4472f1; bottom: 10px; right: 5px; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; cursor: pointer;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="profile_photo_input" class="d-none" accept="image/*">
                            <input type="hidden" name="profile_photo_base64" id="profile_photo_base64">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-1 text-muted"></i> Nombre Completo</label>
                                <input type="text" name="name" class="form-control" placeholder="Ej. Miguel Ibarra" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-envelope mr-1 text-muted"></i> Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="correo@softwaretech.com" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user-shield mr-1 text-muted"></i> Rol Inicial</label>
                                <select name="role" class="form-control" required>
                                    <option value="" disabled selected>Selecciona un rol...</option>
                                    <option value="client">Cliente</option>
                                    <option value="developer">Desarrollador</option>
                                    <option value="admin">Administrador</option>
                                    <option value="superadmin">Superadmin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-lock mr-1 text-muted"></i> Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn text-white px-5 text-bold shadow" style="background-color: #4472f1;">
                            <i class="fas fa-plus-circle mr-1"></i> Registrar Usuario
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default px-4 ml-2 border">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Recorte --}}
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #4472f1;">
                <h5 class="modal-title text-bold">Ajustar posición</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <div class="img-container">
                    <img id="image-to-crop" src="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn text-white btn-block text-bold" style="background-color: #4472f1;" id="save-crop">Listo</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper;
    const photoInput = document.getElementById('profile_photo_input');
    const imageToCrop = document.getElementById('image-to-crop');
    const cropModal = $('#cropModal');

    photoInput.addEventListener('click', function() { this.value = null; });

    photoInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                cropModal.modal('show');
            };
            reader.readAsDataURL(files[0]);
        }
    });

    cropModal.on('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            dragMode: 'move',
            cropBoxResizable: false
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
    });

    document.getElementById('save-crop').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        const base64Data = canvas.toDataURL('image/jpeg');
        
        document.getElementById('preview-image').src = base64Data;
        document.getElementById('preview-image').style.display = 'block';
        document.getElementById('placeholder-icon').style.display = 'none';
        
        document.getElementById('profile_photo_base64').value = base64Data;
        cropModal.modal('hide');
    });
</script>
@endpush