@extends('layout')

@section('title', 'Rezervasyonları Yönet')

<div class="container">
    @section('content')
    <h2 class="mb-4">Rezervasyon İşlemleri</h2>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#pending" data-bs-toggle="tab">Bekleyen İstekler</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#approved" data-bs-toggle="tab">Onaylanan Rezervasyonlar</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="pending">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kitap Adı</th>
                            <th>ISBN</th>
                            <th>Barkod</th>
                            <th>Kullanıcı ID</th>
                            <th>Üye</th>
                            <th>Telefon</th>
                            <th>İstek Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingReservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ $reservation->bookCopy->book->book_name }}</td>
                            <td>{{ $reservation->bookCopy->book->isbn }}</td>
                            <td>{{ $reservation->bookCopy->barcode }}</td>
                            <td>{{ $reservation->user->id }}</td>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->user->tel }}</td>
                            <td>{{ $reservation->request_date }}</td>
                            <td>
                                <button class="btn btn-success btn-sm approve-reservation" data-id="{{ $reservation->id }}">
                                    Onayla
                                </button>
                                <button class="btn btn-danger btn-sm reject-reservation" data-id="{{ $reservation->id }}">
                                    Reddet
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Bekleyen rezervasyon isteği yok</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="approved">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                        <th>ID</th>
                            <th>Kitap Adı</th>
                            <th>ISBN</th>
                            <th>Barkod</th>
                            <th>Kullanıcı ID</th>
                            <th>Üye</th>
                            <th>Telefon</th>
                            <th>Onay Tarihi</th>
                            <th>Durum</th>
                            <th>İşlemler</th> <!-- Yeni kolon eklendi -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvedReservations as $reservation)
                        <tr>
                        <td>{{ $reservation->id }}</td>
                            <td>{{ $reservation->bookCopy->book->book_name }}</td>
                            <td>{{ $reservation->bookCopy->book->isbn }}</td>
                            <td>{{ $reservation->bookCopy->barcode }}</td>
                            <td>{{ $reservation->user->id }}</td>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->user->tel }}</td>
                            <td>{{ $reservation->approval_date }}</td>
                            <td>
                                <span class="badge bg-success">Onaylandı</span>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm cancel-reservation" data-id="{{ $reservation->id }}">
                                    Rezervasyonu İptal Et
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Onaylanmış rezervasyon yok</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection
</div>

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.approve-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if(confirm('Bu rezervasyonu onaylamak istediğinizden emin misiniz?')) {
                fetch(`/adminpanel/reservations/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Bir hata oluştu');
                    }
                });
            }
        });
    });

    document.querySelectorAll('.reject-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if(confirm('Bu rezervasyonu reddetmek istediğinizden emin misiniz?')) {
                fetch(`/adminpanel/reservations/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Bir hata oluştu');
                    }
                });
            }
        });
    });

    // İptal işlemi için yeni kod
    document.querySelectorAll('.cancel-reservation').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if(confirm('Bu rezervasyonu iptal etmek istediğinizden emin misiniz?')) {
                fetch(`/adminpanel/reservations/${id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Bir hata oluştu');
                    }
                });
            }
        });
    });
});
</script>
@endsection
