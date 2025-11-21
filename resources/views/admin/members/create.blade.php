@extends('layout')

@section('title', 'Yeni Üye Ekle')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Yeni Üye Ekle</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.storeUser') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tcno" class="form-label">TC Kimlik No</label>
                            <input type="text" class="form-control @error('tcno') is-invalid @enderror" 
                                   id="tcno" name="tcno" value="{{ old('tcno') }}" required 
                                   pattern="[0-9]{11}" maxlength="11">
                            @error('tcno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
<br>
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tel" class="form-label">Telefon</label>
                            <input type="tel" class="form-control @error('tel') is-invalid @enderror" 
                                   id="tel" name="tel" value="{{ old('tel') }}" required>
                            @error('tel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Şifre Tekrar</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
                                <label class="form-check-label" for="is_admin">Yönetici Yetkisi</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Üye Ekle</button>
                            <a href="{{ route('admin.listUsers') }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
