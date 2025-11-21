@extends('layout')

@section('title', 'Kullanıcı Profilleri')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Kullanıcı Profilleri</h2>
            
            <!-- Arama Formu -->
            <form action="{{ route('admin.userProfiles') }}" method="GET" class="mt-3">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" 
                           placeholder="TC Kimlik No ile ara..." 
                           value="{{ request('search') }}"
                           pattern="[0-9]{11}" maxlength="11">
                    <button class="btn btn-primary" type="submit">Ara</button>
                    @if(request('search'))
                        <a href="{{ route('admin.userProfiles') }}" class="btn btn-secondary">Temizle</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @foreach($users as $user)
        <div class="col-md-6 mb-4">
            <a href="{{ route('admin.userDetail', $user->id) }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-primary' }}">
                            {{ $user->isAdmin() ? 'Admin' : 'Üye' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <img src="{{ asset('avatars/' . ($user->avatar ?? 'default-avatar.png')) }}" 
                                     class="rounded-circle" alt="Profil Resmi" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <p><strong>TC:</strong> {{ $user->tcno }}</p>
                                <p><strong>E-posta:</strong> {{ $user->email }}</p>
                                <p><strong>Telefon:</strong> {{ $user->tel }}</p>
                                <p><strong>Kayıt Tarihi:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6 class="mb-3">Ödünç Alınan Kitaplar</h6>
                            @if($user->borrowings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Kitap</th>
                                                <th>Alış Tarihi</th>
                                                <th>İade Tarihi</th>
                                                <th>Durum</th>
                                                <th>Notlar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->borrowings as $borrowed)
                                            <tr>
                                                <td>{{ optional($borrowed->copy->book)->book_name ?? 'Kitap Bulunamadı' }}</td>
                                                <td>{{ $borrowed->purchase_date }}</td>
                                                <td>{{ $borrowed->returned_at ?? $borrowed->return_date }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $borrowed->status_color }}">
                                                        {{ $borrowed->status_text }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($borrowed->delay_day > 0)
                                                        <span class="text-danger">{{ $borrowed->delay_day }} gün gecikme</span>
                                                    @endif
                                                    {{ $borrowed->notes }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Henüz kitap ödünç alınmamış.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
