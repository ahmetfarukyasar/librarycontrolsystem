@extends('layout')

@section('title', 'Arkadaşlar')

@section('content')

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Arkadaşlarım</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFriendModal">
                <i class="fas fa-user-plus"></i> Arkadaş Ekle
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col">
            @if(count($friends ?? []) > 0)
                <div class="list-group">
                    @foreach($friends ?? [] as $friend)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $friend->friend->name }}</h5>
                            <small class="text-muted">ID: {{ $friend->friend->id }}</small>
                        </div>
                        <form action="/friends/remove/{{ $friend->id }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu arkadaşı silmek istediğinizden emin misiniz?')">
                                <i class="fas fa-user-minus"></i> Arkadaşı Sil
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    Henüz arkadaşınız bulunmamaktadır.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Arkadaş Ekleme Modal -->
<div class="modal fade" id="addFriendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Arkadaş Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/friends/add" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="friendId" class="form-label">Kullanıcı ID</label>
                        <input type="text" class="form-control" id="friendId" name="friend_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="friendName" class="form-label">Kullanıcı Adı</label>
                        <input type="text" class="form-control" id="friendName" name="friend_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Ekle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection