@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="auth-page d-flex align-items-center justify-content-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-10">
                <div class="auth-card mx-auto">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold text-primary mb-3">Verifica tu correo</h5>

                            @if (session('resent'))
                                <div class="alert alert-success small mb-3" role="alert">
                                    Se ha enviado un nuevo enlace de verificación.
                                </div>
                            @endif

                            <p class="text-muted small mb-3">
                                Antes de continuar, revisa tu correo electrónico para el enlace de verificación.
                            </p>

                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    Reenviar enlace
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
