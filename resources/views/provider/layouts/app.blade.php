<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <!-- PWA -->
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="ZYGA">
    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <title>@yield('title', 'ZYGA | Provider')</title>

    <link rel="stylesheet" href="{{ asset('css/user-portal.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $sessionUser = session('user', []);
        $userName = $sessionUser['name'] ?? 'Proveedor';
        $userEmail = $sessionUser['email'] ?? '';
        $avatarLetter = strtoupper(substr($userName ?: $userEmail, 0, 1));
    @endphp

    <div class="app-shell">
        <header class="topbar">
            <div>
                <p class="eyebrow">ZYGA Provider</p>
                <h1 class="page-title">@yield('page-title', 'Panel de proveedor')</h1>
            </div>

            <div class="avatar-circle">{{ $avatarLetter }}</div>
        </header>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Revisa la información capturada.</strong>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main class="page-content">
            @yield('content')
        </main>

        @include('provider.partials.bottom-nav')
    </div>
</body>
</html>
