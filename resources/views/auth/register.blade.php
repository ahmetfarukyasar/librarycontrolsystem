@extends('layout')

@section('title', 'Kayıt')

@section('css')
<style>
.auth-container {
    max-width: 400px;
    margin: auto;
    position: relative;
    min-height: 480px;
}

.auth-form {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    position: absolute;
    width: 100%;
    transition: all 0.5s ease;
}

.auth-container.show-register .register-form {
    transform: translateX(0);
    opacity: 1;
}

.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
}

.auth-toggle {
    text-align: center;
    margin-top: 1rem;
}

.auth-toggle a {
    color: #0d6efd;
    text-decoration: none;
    cursor: pointer;
}
</style>
@endsection

@section('content')
<div class="auth-container">
    <form class="auth-form register-form" action="{{ route('register') }}" method="POST">
        @csrf
        <h2 class="text-center mb-4">Kayıt Ol</h2>
        <div class="mb-3">
            <input type="text" class="form-control" name="name" placeholder="Ad Soyad" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" name="tcno" placeholder="TC Kimlik No" pattern="[0-9]{11}" maxlength="11" required>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="E-posta" required>
        </div>
        <div class="mb-3">
            <input type="tel" class="form-control" name="tel" placeholder="Telefon Numarası" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Şifre" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" name="password_confirmation" placeholder="Şifre Tekrar" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
        <div class="auth-toggle">
            Zaten hesabın var mı? <a href="/login">Giriş yap</a>
        </div>
    </form>
    @if ($errors->any())
        <div class="alert alert-danger mt-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

@endsection