@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1>{{ $title }}</h1>
        <p class="text-muted">Сводная статистика игровых компьютеров клуба, рассчитанная с помощью методов коллекций Laravel (<code>avg()</code>, <code>min()</code>, <code>max()</code>, <code>count()</code>).</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-primary">
                <div class="card-body">
                    <div class="text-muted small">Всего компьютеров (count)</div>
                    <div class="display-6 fw-bold text-primary my-2">{{ $count }}</div>
                    <div class="small text-muted">в трех игровых зонах</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-success">
                <div class="card-body">
                    <div class="text-muted small">Мин. цена в час (min)</div>
                    <div class="display-6 fw-bold text-success my-2">{{ number_format($minPrice, 2, ',', ' ') }} ₽</div>
                    <div class="small text-muted">в стандартном зале</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-warning">
                <div class="card-body">
                    <div class="text-muted small">Средняя цена в час (avg)</div>
                    <div class="display-6 fw-bold text-warning my-2">{{ number_format($avgPrice, 2, ',', ' ') }} ₽</div>
                    <div class="small text-muted">по всем зонам клуба</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-danger">
                <div class="card-body">
                    <div class="text-muted small">Макс. цена в час (max)</div>
                    <div class="display-6 fw-bold text-danger my-2">{{ number_format($maxPrice, 2, ',', ' ') }} ₽</div>
                    <div class="small text-muted">в премиум VIP зале</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Дополнительные показатели</h5>
            <p class="card-text">
                Средний объем оперативной памяти (RAM): <strong>{{ $avgRam }} ГБ</strong>.
            </p>
        </div>
    </div>

    <div>
        <a href="{{ route('catalog.index') }}" class="btn btn-secondary">&larr; Вернуться в каталог</a>
    </div>
@endsection
