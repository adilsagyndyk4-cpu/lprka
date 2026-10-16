@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-cyber p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 rounded bg-dark border border-secondary text-success">
                        <i class="bi bi-plus-circle fs-3"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-0 text-white">{{ $title }}</h1>
                        <p class="text-secondary small mb-0">Заполните все поля для добавления нового компьютера в каталог</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('catalog.store') }}">
                    @csrf
                    @include('catalog._form')
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Создать
                        </button>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
