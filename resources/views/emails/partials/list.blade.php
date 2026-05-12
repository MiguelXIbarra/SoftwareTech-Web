@forelse($emails as $email)
<div class="card shadow-sm mb-2 email-card">
    <div class="card-body py-2 px-3">
        <div class="row align-items-center">
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-link p-0 star-toggle"
                    data-url="{{ route('emails.toggleImportant', $email->id) }}" title="Marcar/Desmarcar como destacado"
                    style="color: inherit;">
                    <i class="{{ $email->is_important ? 'fas text-warning' : 'far text-muted' }} fa-star"></i>
                </button>
            </div>

            <div class="col-md-2">
                <span class="text-white font-weight-bold">{{ $email->sender->name ?? 'Sistema Tech' }}</span>
            </div>

            <div class="col-md-5 d-flex flex-column">
                <span class="text-white font-weight-bold" style="font-size: 1.05rem;">
                    {{ $email->subject ?? 'Sin Asunto' }}
                </span>
                <span class="text-muted small text-truncate">
                    {{ Str::limit(strip_tags($email->content), 90) }}
                </span>
                @if($email->attachment)
                <span class="badge badge-secondary mt-1">Adjunto</span>
                @endif
            </div>

            <div class="col-md-2 text-center">
                <span class="text-muted small">
                    {{ $email->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            <div class="col-md-2 text-right">
                <div class="action-container">
                    <a href="{{ route('emails.show', $email->id) }}" class="btn-glow" title="Ver">
                        <i class="fas fa-eye" style="color: #45a1b5;"></i>
                    </a>
                    @if($email->sender_id === Auth::id())
                    <a href="{{ route('emails.edit', $email->id) }}" class="btn-glow" title="Editar">
                        <i class="fas fa-edit" style="color: #ffc107;"></i>
                    </a>
                    <form action="{{ route('emails.destroy', $email->id) }}" method="POST" id="del-{{ $email->id }}"
                        style="display:inline">
                        @csrf @method('DELETE')
                        <button type="button" class="btn-glow" onclick="confirmDelete('del-{{ $email->id }}')">
                            <i class="fas fa-trash text-danger"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
    <p class="h5 text-muted">Tu bandeja de entrada está vacía.</p>
</div>
@endforelse