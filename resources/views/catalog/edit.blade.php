@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-cyber p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 rounded bg-dark border border-secondary text-warning">
                        <i class="bi bi-pencil-square fs-3"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-0 text-white">{{ $title }}</h1>
                        <p class="text-secondary small mb-0">Измените данные компьютера и сохраните изменения</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('catalog.update', $computer) }}">
                    @csrf
                    @method('PUT')
                    @include('catalog._form')
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="bi bi-check-lg me-1"></i>Сохранить
                        </button>
                        <a href="{{ route('catalog.show', $computer) }}" class="btn btn-outline-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
