<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Без названия' }} — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('catalog.index') }}">{{ config('app.name') }}</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="{{ route('catalog.index') }}">Каталог</a>
                <a class="nav-link" href="{{ route('catalog.zone') }}">Зоны</a>
                <a class="nav-link" href="{{ route('catalog.stats') }}">Статистика</a>
            </div>
            <div class="navbar-nav">
                <a class="nav-link text-light" href="{{ route('feedback.form') }}">Обратная связь</a>
            </div>
        </div>
    </nav>

    <main class="container flex-grow-1">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-light border-top py-3 mt-4">
        <div class="container small text-muted">
            {{ config('lab.student') }} ({{ config('lab.group') }}, N = {{ config('lab.number') }})
            · код занятия: <strong>{{ config('lab.code') }}</strong>
            · {{ now()->format('d.m.Y H:i:s') }}
        </div>
    </footer>
</body>
</html>
