@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-cyber overflow-hidden">
                <div class="card-header bg-dark border-bottom border-secondary d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pc-display text-info fs-4"></i>
                        <h2 class="h5 mb-0 text-white">Компьютер: {{ $computer['inventory_number'] }}</h2>
                    </div>
                    @if ($computer['is_vip'])
                        <span class="badge badge-vip px-3 py-2">
                            <i class="bi bi-star-fill me-1"></i>VIP место
                        </span>
                    @else
                        <span class="badge bg-secondary text-dark px-3 py-2">Стандартное место</span>
                    @endif
                </div>

                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-hash me-1"></i>ID записи
                                </span>
                                <span class="fs-5 fw-bold text-white">{{ $computer['id'] }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-upc-scan me-1"></i>Код (CODE)
                                </span>
                                <span class="badge bg-info text-dark font-monospace fs-6">{{ $computer['code'] }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-tag me-1"></i>Инвентарный номер
                                </span>
                                <span class="fs-5 fw-bold text-info">{{ $computer['inventory_number'] }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-geo-alt me-1"></i>Зона и этаж
                                </span>
                                <a href="{{ route('catalog.zone', ['zone' => $computer['zone']]) }}" class="fs-5 fw-bold text-decoration-none text-white">
                                    {{ $computer['zone'] }}
                                </a>
                                <span class="badge bg-secondary ms-1">{{ $computer['floor'] }} этаж</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-cpu me-1"></i>Процессор (CPU)
                                </span>
                                <span class="fs-5 fw-semibold text-light">{{ $computer['cpu'] }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-memory me-1"></i>Оперативная память
                                </span>
                                <span class="fs-5 fw-bold text-light">{{ $computer['ram_gb'] }} ГБ</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-cash-stack me-1"></i>Стоимость аренды
                                </span>
                                <span class="fs-4 fw-bold text-success">{{ number_format($computer['hour_price'], 2, ',', ' ') }} ₽</span>
                                <span class="text-secondary small">/ час</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded bg-dark border border-secondary">
                                <span class="text-secondary small d-block mb-1">
                                    <i class="bi bi-shield-check me-1"></i>VIP статус
                                </span>
                                <span class="text-light">
                                    {{ $computer['is_vip'] ? 'Да (премиальное геймерское кресло, топовые мониторы 240Hz и девайсы)' : 'Нет (стандартный игровой зал)' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-dark border-top border-secondary d-flex justify-content-between align-items-center py-3">
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>&larr; К каталогу
                    </a>
                    <a href="{{ route('catalog.code', ['code' => $computer['code']]) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-link-45deg me-1"></i>Прямая ссылка по коду
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
