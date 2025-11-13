<footer class="admin-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="bi bi-shield-check me-2"></i>Panel de Administración</h5>
                <p class="text-muted mb-1">Hakipadel - Sistema de Gestión</p>
                <small class="text-muted">© {{ date('Y') }} Todos los derechos reservados</small>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="mb-2">
                    <i class="bi bi-person-circle me-2"></i>
                    <small>{{ Auth::user()->name }}</small>
                </div>
                <div class="mb-2">
                    <i class="bi bi-envelope me-2"></i>
                    <small>{{ Auth::user()->email }}</small>
                </div>
                <div class="mt-3">
                    <a href="https://wa.me/573112172009" target="_blank" class="me-3">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                    <a href="{{ route('contacto.index') }}">
                        <i class="bi bi-question-circle"></i> Ayuda
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
