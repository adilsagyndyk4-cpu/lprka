<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Без названия' }} — {{ config('app.name') }}</title>
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter & Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --cyber-primary: #38bdf8;
            --cyber-secondary: #a855f7;
            --cyber-accent: #f59e0b;
            --cyber-bg: #0b0f19;
            --cyber-card: #111827;
            --cyber-card-border: #1f2937;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--cyber-bg);
            color: #e2e8f0;
            background-image: 
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
        }

        h1, h2, h3, .navbar-brand, .cyber-font {
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .navbar-cyber {
            background: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(56, 189, 248, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }

        .navbar-brand {
            font-size: 1.4rem;
            color: #fff !important;
            text-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.2s ease;
            color: #94a3b8 !important;
            margin: 0 4px;
            border-radius: 6px;
            padding: 6px 12px !important;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--cyber-primary) !important;
            background: rgba(56, 189, 248, 0.1);
        }

        .card-cyber {
            background-color: var(--cyber-card);
            border: 1px solid var(--cyber-card-border);
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-cyber:hover {
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 12px 30px -5px rgba(56, 189, 248, 0.15);
        }

        .table-cyber {
            --bs-table-bg: transparent;
            --bs-table-hover-bg: rgba(56, 189, 248, 0.05);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-cyber th {
            background-color: #1e293b;
            color: #94a3b8;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #334155;
            padding: 12px 16px;
        }

        .table-cyber td {
            padding: 14px 16px;
            border-bottom: 1px solid #1e293b;
            vertical-align: middle;
        }

        .btn-cyber-primary {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            border: 1px solid #38bdf8;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-cyber-primary:hover {
            background: linear-gradient(135deg, #0369a1, #0284c7);
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
            color: #fff;
            transform: translateY(-1px);
        }

        .form-control, .form-select {
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #e2e8f0;
            border-radius: 8px;
        }

        .form-control:focus, .form-select:focus {
            background-color: #0f172a;
            border-color: var(--cyber-primary);
            box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.2);
            color: #fff;
        }

        .badge-vip {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #000;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.4);
        }

        .footer-cyber {
            background: #0f172a;
            border-top: 1px solid #1e293b;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-cyber sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('catalog.index') }}">
                <i class="bi bi-controller text-info fs-4"></i>
                <span>{{ config('app.name') }}</span>
            </a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="{{ route('catalog.index') }}">
                    <i class="bi bi-display me-1"></i>Каталог
                </a>
                <a class="nav-link" href="{{ route('catalog.zone') }}">
                    <i class="bi bi-grid-3x3-gap me-1"></i>Зоны
                </a>
                <a class="nav-link" href="{{ route('catalog.stats') }}">
                    <i class="bi bi-graph-up-arrow me-1"></i>Статистика
                </a>
            </div>
            <div class="navbar-nav">
                <a class="nav-link btn btn-outline-info btn-sm text-info px-3" href="{{ route('feedback.form') }}">
                    <i class="bi bi-chat-dots me-1"></i>Обратная связь
                </a>
            </div>
        </div>
    </nav>

    <main class="container flex-grow-1">
        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center gap-2 shadow-sm border-0 mb-4" role="alert" style="background-color: rgba(16, 185, 129, 0.15); border-left: 4px solid #10b981 !important; color: #6ee7b7;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="footer-cyber py-3 mt-5">
        <div class="container small text-muted d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <i class="bi bi-person-badge text-info me-1"></i>
                {{ config('lab.student') }} ({{ config('lab.group') }}, N = {{ config('lab.number') }})
            </div>
            <div>
                код занятия: <strong class="badge bg-dark border border-secondary text-info px-2 py-1 font-monospace">{{ config('lab.code') }}</strong>
            </div>
            <div>
                <i class="bi bi-clock me-1"></i>{{ now()->format('d.m.Y H:i:s') }}
            </div>
        </div>
    </footer>
</body>
</html>
