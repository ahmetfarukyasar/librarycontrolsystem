@extends('layout')

@section('title', 'Giriş')

@section('css')
<style>
.auth-container {
    max-width: 400px;
    margin: 2rem auto;
    position: relative;
    min-height: 280px;
}
.auth-form {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
}
.auth-toggle {
    text-align: center;
    margin-top: 1rem;
}
.auth-toggle a { color: #0d6efd; text-decoration: none; }
</style>
@endsection

@section('content')
<div class="auth-container">
    <form class="auth-form login-form" action="{{ route('login') }}" method="POST">
        @csrf
        <h2 class="text-center mb-4">Giriş Yap</h2>

        <div class="mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="E-posta" required value="{{ old('email') }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Şifre" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Beni Hatırla</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>

        <div class="auth-toggle">
            Hesabın yok mu? <a href="/register">Kayıt ol</a>
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