<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Separacao' }} - {{ config('app.name', 'Z Brothers') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $css = file_get_contents(resource_path('css/app.css'));
            $customCss = substr($css, strpos($css, ':root'));
        @endphp
        <style>{!! $customCss !!}</style>
    @endif
</head>
<body class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('picking-operator-goals.index') }}">
            <span class="brand-mark">ZB</span>
            <span>
                <strong>Z Brothers</strong>
                <small>Picking</small>
            </span>
        </a>

        <nav class="nav-list" aria-label="Principal">
            <a href="{{ route('picking-operator-goals.index') }}" @class(['active' => request()->routeIs('picking-operator-goals.*') || request()->routeIs('dashboard')])>Dashboard</a>
            <a href="{{ route('product-picking.index') }}" @class(['active' => request()->routeIs('product-picking.*')])>Separacoes</a>
            <a href="{{ route('picking-operators.index') }}" @class(['active' => request()->routeIs('picking-operators.*')])>Operadores</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="page-header">
            <div>
                <p class="eyebrow">{{ $eyebrow ?? 'Operacao de picking' }}</p>
                <h1>{{ $title ?? 'Dashboard' }}</h1>
            </div>
            <div class="header-actions">
                {{ $actions ?? '' }}
            </div>
        </header>

        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert danger">
                <strong>Revise os campos destacados.</strong>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
