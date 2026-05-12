@extends('layouts.page')
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

                <form action="{{ route('emails.update', $emails->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="form-group mb-4">
                            <label class="small text-uppercase text-bold" style="color: #ffc107;">Asunto del
                                Correo</label>
                            <input type="text" name="subject" class="form-control bg-dark text-white border-secondary"
                                value="{{ old('subject', $emails->subject) }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small text-uppercase text-bold" style="color: #ffc107;">Adjunto</label>
                            @if($emails->attachment)
                            @php
                            $attachmentName = $emails->attachment_name ?? basename($emails->attachment);
                            $attachmentUrl = route('emails.attachment', $emails->id);
                            $extension = strtolower(pathinfo($emails->attachment, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg','jpeg','png','gif','webp','bmp','svg']);
                            $isPdf = $extension === 'pdf';
                            @endphp
                            <div class="d-flex align-items-center justify-content-between rounded p-3 mb-3"
                                style="border: 1px solid rgba(255,255,255,0.08); background: rgba(10, 18, 32, 0.85);">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3"
                                        style="width: 42px; height: 42px; display: grid; place-items: center; background: rgba(69, 161, 181, 0.15); border-radius: 10px;">
                                        <i class="fas fa-paperclip text-info"></i>
                                    </div>
                                    <div>
                                        <div class="text-white font-weight-bold">{{ $attachmentName }}</div>
                                        <small class="text-muted">Archivo adjunto actual</small>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    @if($isImage || $isPdf)
                                    <button type="button" class="btn btn-sm btn-outline-light attachment-preview"
                                        data-url="{{ $attachmentUrl }}" data-type="{{ $isImage ? 'image' : 'pdf' }}">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </button>
                                    @endif
                                    <a href="{{ $attachmentUrl }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-download mr-1"></i> Descargar
                                    </a>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remove_attachment"
                                    id="remove_attachment" value="1">
                                <label class="form-check-label text-white" for="remove_attachment">
                                    Eliminar archivo actual
                                </label>
                            </div>
                            @endif
                            <div class="custom-file mb-2">
                                <input type="file" name="attachment"
                                    class="form-control bg-dark text-white border-secondary">
                            </div>
                            <small class="form-text text-muted">Selecciona un archivo nuevo para reemplazar el adjunto
                                actual o deja el actual si solo quieres editar asunto/contenido.</small>
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

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const previewButtons = document.querySelectorAll('.attachment-preview');

        previewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const url = this.dataset.url;
                const type = this.dataset.type;

                if (!url) {
                    return;
                }

                const overlay = document.createElement('div');
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.right = '0';
                overlay.style.bottom = '0';
                overlay.style.width = '100vw';
                overlay.style.height = '100vh';
                overlay.style.zIndex = '2147483647';
                overlay.style.background = 'rgba(0, 0, 0, 0.78)';
                overlay.style.backdropFilter = 'blur(10px)';
                overlay.style.display = 'grid';
                overlay.style.placeItems = 'center';
                overlay.style.padding = '1rem';
                overlay.style.overflow = 'auto';

                const dialog = document.createElement('div');
                dialog.style.width = 'min(95vw, 1100px)';
                dialog.style.maxHeight = '90vh';
                dialog.style.background = '#10141f';
                dialog.style.border = '1px solid rgba(255,255,255,0.12)';
                dialog.style.borderRadius = '16px';
                dialog.style.overflow = 'hidden';
                dialog.style.boxShadow = '0 20px 50px rgba(0,0,0,0.55)';
                dialog.style.display = 'flex';
                dialog.style.flexDirection = 'column';
                dialog.style.width = '100%';

                const header = document.createElement('div');
                header.style.display = 'flex';
                header.style.alignItems = 'center';
                header.style.justifyContent = 'space-between';
                header.style.padding = '1rem 1.25rem';
                header.style.background = 'rgba(255,255,255,0.04)';
                header.style.borderBottom = '1px solid rgba(255,255,255,0.08)';

                const title = document.createElement('div');
                title.innerHTML = `<h5 style="margin:0;color:#ffffff;font-size:1.05rem;">Vista previa de archivo</h5><small style="color:rgba(255,255,255,0.65);">${url.split('/').pop()}</small>`;

                const closeButton = document.createElement('button');
                closeButton.type = 'button';
                closeButton.innerHTML = '&times;';
                closeButton.style.background = 'transparent';
                closeButton.style.border = 'none';
                closeButton.style.color = '#ffffff';
                closeButton.style.fontSize = '1.5rem';
                closeButton.style.cursor = 'pointer';

                const body = document.createElement('div');
                body.style.flex = '1';
                body.style.position = 'relative';
                body.style.background = '#0b0f18';
                body.style.display = 'grid';
                body.style.placeItems = 'center';
                body.style.minHeight = '60vh';

                const contentContainer = document.createElement('div');
                contentContainer.style.width = '100%';
                contentContainer.style.height = '100%';
                contentContainer.style.display = 'grid';
                contentContainer.style.placeItems = 'center';

                if (type === 'image') {
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Vista previa';
                    img.style.maxWidth = '100%';
                    img.style.maxHeight = '100%';
                    img.style.objectFit = 'contain';
                    contentContainer.appendChild(img);
                } else if (type === 'pdf') {
                    const iframe = document.createElement('iframe');
                    iframe.src = url;
                    iframe.style.width = '100%';
                    iframe.style.height = '100%';
                    iframe.style.border = 'none';
                    contentContainer.appendChild(iframe);
                } else {
                    const message = document.createElement('div');
                    message.style.display = 'flex';
                    message.style.alignItems = 'center';
                    message.style.justifyContent = 'center';
                    message.style.height = '100%';
                    message.style.color = '#ffffff';
                    message.style.padding = '1.5rem';
                    message.style.textAlign = 'center';
                    message.textContent = 'No se puede previsualizar este tipo de archivo. Descarga el documento para abrirlo.';
                    contentContainer.appendChild(message);
                }

                body.appendChild(contentContainer);
                header.appendChild(title);
                header.appendChild(closeButton);
                dialog.appendChild(header);
                dialog.appendChild(body);
                overlay.appendChild(dialog);
                document.body.appendChild(overlay);

                function removeOverlay() {
                    if (overlay.parentElement) {
                        overlay.parentElement.removeChild(overlay);
                    }
                }

                closeButton.addEventListener('click', removeOverlay);
                overlay.addEventListener('click', function (event) {
                    if (event.target === overlay) {
                        removeOverlay();
                    }
                });
            });
        });
    });
</script>
@endsection