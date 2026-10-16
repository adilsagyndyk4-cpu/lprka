@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="display-6 mb-1 text-white">{{ $title }}</h1>
            <p class="text-secondary small mb-0">Каталог игровых мест киберспортивного клуба с актуальными конфигурациями</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('catalog.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Добавить ПК
            </a>
            <a href="{{ route('catalog.zone') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-grid me-1"></i>Все зоны
            </a>
            <a href="{{ route('catalog.stats') }}" class="btn btn-outline-info btn-sm">
                <i class="bi bi-bar-chart-line me-1"></i>Статистика
            </a>
        </div>
    </div>

    <!-- Фильтр и поиск -->
    <div class="card card-cyber p-3 mb-4">
        <form method="GET" action="{{ route('catalog.index') }}" class="row g-2 align-items-center">
            <div class="col-lg-3 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Поиск (инв. №, CPU, код)...">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary">
                        <i class="bi bi-geo-alt"></i>
                    </span>
                    <select name="zone_id" class="form-select">
                        <option value="">Все зоны</option>
                        @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}" @selected($zoneId == $zone->id)>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="is_vip" class="form-select">
                    <option value="">Все категории</option>
                    <option value="1" @selected($isVip === '1')>Только VIP</option>
                    <option value="0" @selected($isVip === '0')>Только Обычные</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary">
                        <i class="bi bi-arrow-down-up"></i>
                    </span>
                    <select name="sort" class="form-select">
                        <option value="inventory_number" @selected($sort === 'inventory_number')>Инв. номер</option>
                        <option value="ram_gb" @selected($sort === 'ram_gb')>ОЗУ</option>
                        <option value="hour_price" @selected($sort === 'hour_price')>Цена часа</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-cyber-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Фильтр
                </button>
                @if ($search || $zoneId || $isVip !== null && $isVip !== '' || $sort !== 'inventory_number')
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary" title="Сбросить все фильтры">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>


    <!-- Таблица ПК -->
    <div class="card card-cyber overflow-hidden">
        <div class="table-responsive">
            <table class="table table-cyber align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">№</th>
                        <th>Инвентарный №</th>
                        <th>Зона (зал)</th>
                        <th>Процессор</th>
                        <th>ОЗУ</th>
                        <th>Цена в час</th>
                        <th class="text-center">Категория</th>
                        <th class="text-center">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($computers as $computer)
                        <tr>
                            <td class="text-center text-secondary small">{{ $computers->firstItem() + $loop->index }}</td>
                            <td>
                                <a href="{{ route('catalog.show', $computer) }}" class="text-info fw-bold text-decoration-none">
                                    {{ $computer->inventory_number }}
                                </a>
                                <span class="badge bg-dark border border-secondary text-secondary font-monospace ms-1 small">
                                    {{ $computer->code }}
                                </span>
                            </td>
                            <td>
                                @if ($computer->zone)
                                    <a href="{{ route('catalog.zone', $computer->zone) }}" class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle text-decoration-none">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $computer->zone->name }} (эт. {{ $computer->zone->floor }})
                                    </a>
                                @endif
                            </td>
                            <td>
                                <span class="text-light">
                                    <i class="bi bi-cpu text-secondary me-1"></i>{{ $computer->cpu }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    <i class="bi bi-memory me-1"></i>{{ $computer->ram_gb }} ГБ
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    {{ number_format($computer->hour_price, 2, ',', ' ') }} ₽
                                </span>
                                <span class="text-secondary small">/ч</span>
                            </td>
                            <td class="text-center">
                                @if ($computer->is_vip)
                                    <span class="badge badge-vip">
                                        <i class="bi bi-star-fill me-1"></i>VIP
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-dark">Обычный</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('catalog.edit', $computer) }}" class="btn btn-outline-warning btn-sm" title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('catalog.destroy', $computer) }}" onsubmit="return confirm('Удалить {{ $computer->inventory_number }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Удалить">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Компьютеры не найдены.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Пагинация PER_PAGE = 4 -->
    @if ($computers->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $computers->links() }}
        </div>
    @endif
@endsection
