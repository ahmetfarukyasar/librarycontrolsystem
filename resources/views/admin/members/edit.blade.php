@extends('layout')

@section('title', 'Üye Düzenle')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Üye Düzenle</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.updateUser', $user->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="tel" class="form-label">Telefon</label>
                            <input type="text" class="form-control" id="tel" name="tel" value="{{ $user->tel }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adres</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ $user->address }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Yeni Şifre (Opsiyonel)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Şifreyi değiştirmek istemiyorsanız boş bırakın</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" {{ $user->is_admin ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_admin">Yönetici Yetkisi</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                            <a href="{{ route('admin.listUsers') }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
