<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Estilos personalizados -->
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <!-- Estilos adicionales de las vistas -->
    @yield('styles')
</head>
<body class="@if(request()->is('login*')) auth-page login-page @elseif(request()->is('register*')) auth-page register-page @elseif(request()->is('verify*')) auth-page verify-page @elseif(request()->is('password/confirm*')) auth-page confirm-password-page @elseif(request()->is('password/reset*')) auth-page reset-password-page @elseif(request()->is('password/email*')) auth-page forgot-password-page @endif">
    <div id="app">
        @auth
            <!-- Navbar solo para usuarios autenticados -->
            @include('layouts.navbar')
        @endauth

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer para todas las páginas -->
        @include('layouts.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Scripts personalizados -->
    <script>
        document.getElementById('dropdownNotificaciones')?.addEventListener('click', () => {
            fetch('{{ route("notificaciones.leer") }}')
        });
    </script>

    <!-- Scripts adicionales de las vistas -->
    @yield('scripts')
</body>
</html>
