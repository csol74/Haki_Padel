protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    // ... otros middlewares
    'limpiar.reservas' => \App\Http\Middleware\LimpiarReservasExpiradas::class, // ← AGREGAR
];
