@extends('layout')

@section('title', 'Keşfet - Kütüphane')

@section('content')
    

    <div class="card mb-4">
        <div class="card-body">
            <form action="/post/create" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea class="form-control" rows="3" name="content" placeholder="Kitaplar hakkında düşüncelerinizi paylaşın..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary float-end">Paylaş</button>
            </form>
        </div>
    </div>


    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary">En Yeniler</button>
                        <button type="button" class="btn btn-outline-secondary">En Popüler</button>
                        <button type="button" class="btn btn-outline-secondary">En Çok Yorumlanan</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select">
                        <option selected>Tür Seçin</option>
                        <option>Roman</option>
                        <option>Bilim Kurgu</option>
                        <option>Tarih</option>
                        <option>Felsefe</option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="posts">

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2">
                    <div>
                        <h6 class="mb-0">Ahmet Yılmaz</h6>
                        <small class="text-muted">3 saat önce</small>
                    </div>
                </div>
                <h5 class="card-title">Suç ve Ceza</h5>
                <div class="mb-2">
                    ⭐⭐⭐⭐⭐ <small class="text-muted">(5/5)</small>
                </div>
                <p class="card-text">Dostoyevski'nin bu başyapıtını okumak muhteşem bir deneyimdi. Karakterlerin psikolojik derinliği ve hikayenin akışı büyüleyiciydi.</p>
                
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-heart"></i> Beğen (124)
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chat"></i> Yorum Yap (45)
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2">
                    <div>
                        <h6 class="mb-0">Ayşe Demir</h6>
                        <small class="text-muted">5 saat önce</small>
                    </div>
                </div>
                <h5 class="card-title">1984</h5>
                <div class="mb-2">
                    ⭐⭐⭐⭐ <small class="text-muted">(4/5)</small>
                </div>
                <p class="card-text">George Orwell'in distopik dünyası günümüzde bile geçerliliğini koruyor. Kesinlikle okunması gereken bir klasik.</p>
                
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-heart"></i> Beğen (89)
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chat"></i> Yorum Yap (23)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<style>
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .posts .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }
</style>
@endsection