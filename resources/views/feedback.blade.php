@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1>{{ $title }}</h1>
            <p class="text-muted">Оставьте ваш отзыв или вопрос по работе компьютерного клуба.</p>

            <form method="POST" action="{{ route('feedback.send') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Ваше имя:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Имя">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Текст сообщения:</label>
                    <textarea id="message" name="message" class="form-control" rows="3" placeholder="Ваше сообщение..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>
@endsection
