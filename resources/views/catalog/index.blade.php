@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $title }}</h1>
        <a href="{{ route('catalog.stats') }}" class="btn btn-outline-info">📊 Статистика</a>
    </div>

    <form method="GET" action="{{ route('catalog.index') }}" class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Поиск по инвентарному номеру, CPU или коду...">
        </div>
        <div class="col-md-4">
            <select name="sort" class="form-select">
                <option value="inventory_number" @selected($sort === 'inventory_number')>По инвентарному номеру (А-Я)</option>
                <option value="ram_gb" @selected($sort === 'ram_gb')>По объему RAM</option>
                <option value="hour_price" @selected($sort === 'hour_price')>По цене за час</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-secondary flex-grow-1">Найти</button>
            @if ($search || $sort !== 'inventory_number')
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">Сброс</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>№</th>
                    <th>Инвентарный №</th>
                    <th>Зона (зал)</th>
                    <th>Процессор</th>
                    <th>ОЗУ</th>
                    <th>Цена в час</th>
                    <th>Категория</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($computers as $computer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('catalog.show', ['id' => $computer['id']]) }}" class="fw-bold text-decoration-none">
                                {{ $computer['inventory_number'] }}
                            </a>
                            <span class="text-muted small">({{ $computer['code'] }})</span>
                        </td>
                        <td>
                            <a href="{{ route('catalog.zone', ['zone' => $computer['zone']]) }}" class="badge bg-primary text-decoration-none">
                                {{ $computer['zone'] }} (эт. {{ $computer['floor'] }})
                            </a>
                        </td>
                        <td>{{ $computer['cpu'] }}</td>
                        <td>{{ $computer['ram_gb'] }} ГБ</td>
                        <td>{{ number_format($computer['hour_price'], 2, ',', ' ') }} ₽</td>
                        <td>
                            @if ($computer['is_vip'])
                                <span class="badge bg-warning text-dark">VIP</span>
                            @else
                                <span class="badge bg-secondary">Обычный</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Компьютеры не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
