@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="display-6 text-white">{{ $title }}</h1>
        <p class="text-secondary">Выберите игровую зону компьютерного клуба для просмотра доступных ПК.</p>
    </div>

    <div class="row g-4">
        @foreach ($zones as $zone)
            <div class="col-md-4">
                <div class="card card-cyber h-100 p-3 position-relative overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <i class="bi bi-controller fs-2 text-info"></i>
                            <span class="badge bg-dark border border-secondary text-secondary">
                                {{ $zone['floor'] }}-й этаж
                            </span>
                        </div>
                        <h4 class="card-title text-white mb-2">{{ $zone['name'] }}</h4>
                        <p class="card-text text-secondary small mb-4">
                            Расположение: <strong>{{ $zone['floor'] }}-й этаж</strong> клуба. Оснащен передовым сетевым оборудованием.
                        </p>
                        <a href="{{ route('catalog.zone', ['zone' => $zone['name']]) }}" class="btn btn-cyber-primary w-100">
                            Смотреть ПК этой зоны &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>&larr; Ко всем компьютерам клуба
        </a>
    </div>
@endsection
