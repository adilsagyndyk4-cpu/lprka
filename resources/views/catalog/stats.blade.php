@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="display-6 text-white">{{ $title }}</h1>
        <p class="text-secondary">Сводная статистика игровых компьютеров клуба, рассчитанная с помощью методов коллекций Laravel (<code>avg()</code>, <code>min()</code>, <code>max()</code>, <code>count()</code>).</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card card-cyber text-center h-100 p-3 border-info-subtle">
                <div class="card-body">
                    <i class="bi bi-pc-display-horizontal fs-1 text-info d-block mb-2"></i>
                    <div class="text-secondary small">Всего компьютеров (count)</div>
                    <div class="display-5 fw-bold text-info my-2">{{ $count }}</div>
                    <div class="small text-secondary">в трех игровых зонах</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-cyber text-center h-100 p-3 border-success-subtle">
                <div class="card-body">
                    <i class="bi bi-arrow-down-circle fs-1 text-success d-block mb-2"></i>
                    <div class="text-secondary small">Мин. цена в час (min)</div>
                    <div class="display-5 fw-bold text-success my-2">{{ number_format($minPrice, 2, ',', ' ') }} ₽</div>
                    <div class="small text-secondary">в стандартном зале</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-cyber text-center h-100 p-3 border-warning-subtle">
                <div class="card-body">
                    <i class="bi bi-graph-up fs-1 text-warning d-block mb-2"></i>
                    <div class="text-secondary small">Средняя цена в час (avg)</div>
                    <div class="display-5 fw-bold text-warning my-2">{{ number_format($avgPrice, 2, ',', ' ') }} ₽</div>
                    <div class="small text-secondary">по всем зонам клуба</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-cyber text-center h-100 p-3 border-danger-subtle">
                <div class="card-body">
                    <i class="bi bi-fire fs-1 text-danger d-block mb-2"></i>
                    <div class="text-secondary small">Макс. цена в час (max)</div>
                    <div class="display-5 fw-bold text-danger my-2">{{ number_format($maxPrice, 2, ',', ' ') }} ₽</div>
                    <div class="small text-secondary">в премиум VIP зале</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-cyber p-4 mb-4">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-memory fs-1 text-primary"></i>
            <div>
                <h5 class="card-title text-white mb-1">Дополнительные показатели оборудования</h5>
                <p class="card-text text-secondary mb-0">
                    Средний объем оперативной памяти (RAM): <strong class="text-info">{{ $avgRam }} ГБ</strong>.
                </p>
            </div>
        </div>
    </div>

    <div>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>&larr; Вернуться в каталог
        </a>
    </div>
@endsection
