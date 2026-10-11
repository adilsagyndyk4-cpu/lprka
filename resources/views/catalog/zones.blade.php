@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1>{{ $title }}</h1>
        <p class="text-muted">Выберите игровую зону компьютерного клуба для просмотра доступных ПК.</p>
    </div>

    <div class="row g-3">
        @foreach ($zones as $zone)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $zone['name'] }}</h5>
                        <p class="card-text text-muted mb-3">Расположение: <strong>{{ $zone['floor'] }}-й этаж</strong></p>
                        <a href="{{ route('catalog.zone', ['zone' => $zone['name']]) }}" class="btn btn-outline-primary w-100">
                            Смотреть ПК этой зоны &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        <a href="{{ route('catalog.index') }}" class="btn btn-secondary">&larr; Ко всем компьютерам клуба</a>
    </div>
@endsection
