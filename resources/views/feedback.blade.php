@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card card-cyber p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 rounded bg-dark border border-secondary text-info">
                        <i class="bi bi-chat-heart fs-3"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-0 text-white">{{ $title }}</h1>
                        <p class="text-secondary small mb-0">Оставьте отзыв или задайте вопрос администраторам клуба</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('feedback.send') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label text-secondary small text-uppercase fw-semibold">
                            <i class="bi bi-person me-1"></i>Ваше имя:
                        </label>
                        <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="Например: Адиль">
                    </div>
                    <div class="mb-4">
                        <label for="message" class="form-label text-secondary small text-uppercase fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i>Текст сообщения:
                        </label>
                        <textarea id="message" name="message" class="form-control" rows="4" placeholder="Напишите ваше сообщение, отзыв или пожелание..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-cyber-primary btn-lg w-100">
                        <i class="bi bi-send me-1"></i>Отправить
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
