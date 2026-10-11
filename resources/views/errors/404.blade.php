@extends('layouts.app')

@section('content')
    <div class="row justify-content-center my-5">
        <div class="col-md-8 text-center">
            <div class="display-1 text-danger fw-bold mb-3">404</div>
            <h2 class="mb-3">Запрошенная страница не найдена</h2>
            <div class="alert alert-warning py-3">
                Запрошенный путь: <strong>/{{ request()->path() }}</strong>
            </div>
            <p class="text-muted mb-4">
                Запрашиваемый ресурс отсутствует, был удален или указан неверный адрес / параметр.
            </p>
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Вернуться в каталог</a>
                <a href="{{ route('feedback.form') }}" class="btn btn-outline-secondary">Связаться с нами</a>
            </div>
        </div>
    </div>
@endsection
