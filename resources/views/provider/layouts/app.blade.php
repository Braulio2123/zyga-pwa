    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Zyga Proveedor')</title>
    <link rel="stylesheet" href="{{ asset('css/user-portal.css') }}">
</head>
<body>
    @php
        $sessionUser = session('user');
        $userName = is_array($sessionUser) ? ($sessionUser['name'] ?? 'Proveedor') : 'Proveedor';
        $userEmail = is_array($sessionUser) ? ($sessionUser['email'] ?? '') : '';
        $avatarLetter = strtoupper(substr($userName ?: $userEmail, 0, 1));
    @endphp

    <div class="app-shell">
        <header class="topbar">
            <div>
                <p class="eyebrow">Zyga proveedor</p>
                <h1 class="page-title">@yield('page-title', 'Panel')</h1>
            </div>

            <div class="avatar-circle">
                {{ $avatarLetter }}
            </div>
        </header>

        <main class="page-content">
            @yield('content')
        </main>

        {{-- Puedes reutilizar el mismo bottom nav o crear uno nuevo --}}
        @include('provider.partials.bottom-nav')
    </div>
</body>
</html>