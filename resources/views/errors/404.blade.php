@extends('layouts.app')

@section('content')
    <div class="row justify-content-center my-5">
        <div class="col-lg-7 text-center">
            <div class="card card-cyber p-5">
                <div class="display-1 text-danger fw-bold cyber-font mb-2" style="text-shadow: 0 0 20px rgba(239, 68, 68, 0.4);">
                    404
                </div>
                <h2 class="h3 text-white mb-3">Запрошенная страница не найдена</h2>
                <div class="alert alert-dark border border-secondary py-3 my-3">
                    <span class="text-secondary small d-block">Запрошенный путь:</span>
                    <code class="text-info fs-5 font-monospace">/{{ request()->path() }}</code>
                </div>
                <p class="text-secondary mb-4">
                    Запрашиваемый ресурс отсутствует, был удален или указан неверный адрес / параметр.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('catalog.index') }}" class="btn btn-cyber-primary px-4">
                        <i class="bi bi-arrow-left me-1"></i>Вернуться в каталог
                    </a>
                    <a href="{{ route('feedback.form') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-headset me-1"></i>Связаться с нами
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
