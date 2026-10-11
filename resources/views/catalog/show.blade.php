@extends('layouts.app')

@section('content')
    <div class="card shadow-sm col-md-8 mx-auto">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Компьютер: {{ $computer['inventory_number'] }}</h2>
            @if ($computer['is_vip'])
                <span class="badge bg-warning text-dark">VIP место</span>
            @else
                <span class="badge bg-secondary">Стандартное место</span>
            @endif
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">ID записи</dt>
                <dd class="col-sm-8">{{ $computer['id'] }}</dd>

                <dt class="col-sm-4">Код (CODE)</dt>
                <dd class="col-sm-8"><span class="badge bg-info text-dark font-monospace">{{ $computer['code'] }}</span></dd>

                <dt class="col-sm-4">Инвентарный номер</dt>
                <dd class="col-sm-8 fw-bold">{{ $computer['inventory_number'] }}</dd>

                <dt class="col-sm-4">Зона клуба</dt>
                <dd class="col-sm-8">
                    <a href="{{ route('catalog.zone', ['zone' => $computer['zone']]) }}" class="text-decoration-none">
                        {{ $computer['zone'] }}
                    </a>
                </dd>

                <dt class="col-sm-4">Этаж зоны</dt>
                <dd class="col-sm-8">{{ $computer['floor'] }} этаж</dd>

                <dt class="col-sm-4">Процессор (CPU)</dt>
                <dd class="col-sm-8">{{ $computer['cpu'] }}</dd>

                <dt class="col-sm-4">Оперативная память</dt>
                <dd class="col-sm-8">{{ $computer['ram_gb'] }} ГБ</dd>

                <dt class="col-sm-4">Стоимость аренды</dt>
                <dd class="col-sm-8 text-success fw-bold">{{ number_format($computer['hour_price'], 2, ',', ' ') }} ₽ / час</dd>

                <dt class="col-sm-4">VIP статус</dt>
                <dd class="col-sm-8">
                    {{ $computer['is_vip'] ? 'Да (премиальное кресло и периферия)' : 'Нет (стандартный зал)' }}
                </dd>
            </dl>
        </div>
        <div class="card-footer bg-light d-flex justify-content-between">
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">&larr; К каталогу</a>
            <a href="{{ route('catalog.code', ['code' => $computer['code']]) }}" class="btn btn-outline-primary btn-sm">Прямая ссылка по коду</a>
        </div>
    </div>
@endsection
