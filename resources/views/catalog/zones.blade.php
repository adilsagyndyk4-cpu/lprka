@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="display-6 text-white">{{ $title }}</h1>
        <p class="text-secondary">Игровые зоны клуба с количеством ПК (withCount) и правилом внешнего ключа CASCADE.</p>
    </div>

    <div class="row g-4">
        @foreach ($zones as $zone)
            <div class="col-md-4">
                <div class="card card-cyber h-100 p-3 position-relative overflow-hidden">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <i class="bi bi-controller fs-2 text-info"></i>
                            <span class="badge bg-dark border border-secondary text-secondary">
                                {{ $zone->floor }}-й этаж
                            </span>
                        </div>
                        <h4 class="card-title text-white mb-2">{{ $zone->name }}</h4>
                        <p class="card-text text-secondary small mb-3">
                            {{ $zone->description ?? 'Оснащена передовым сетевым оборудованием и удобными местами.' }}
                        </p>
                        
                        <div class="mb-4">
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-2 fs-6">
                                <i class="bi bi-pc-display me-1"></i>Компьютеров в зоне: <strong>{{ $zone->computers_count }}</strong>
                            </span>
                        </div>

                        <div class="mt-auto d-flex flex-column gap-2">
                            <a href="{{ route('catalog.zone', $zone) }}" class="btn btn-cyber-primary w-100">
                                Смотреть ПК этой зоны &rarr;
                            </a>
                            <form method="POST" action="{{ route('catalog.zone.destroy', $zone) }}"
                                  onsubmit="return confirm('Внимание! Включен режим ON DELETE CASCADE!\nПри удалении зоны «{{ $zone->name }}» будут также БЕЗВОЗВРАТНО УДАЛЕНЫ ВСЕ связанные компьютеры ({{ $zone->computers_count }} шт.)!\n\nВы уверены?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="bi bi-trash3 me-1"></i>Удалить зону (CASCADE)
                                </button>
                            </form>
                        </div>
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
