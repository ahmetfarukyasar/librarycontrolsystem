@extends('layout')

@section('title', 'Ödünç İşlemlerini Yönet')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Ödünç İşlemleri</h2>
            <div class="d-flex justify-content-between mb-3">
                <form class="d-flex gap-2" method="GET" action="{{ route('admin.manageCopies') }}">
                    <input type="text" name="search" class="form-control" placeholder="ISBN veya Barkod ile ara..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">Ara</button>
                    @if(request('search'))
                        <a href="{{ route('admin.manageCopies') }}" class="btn btn-outline-secondary">Temizle</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kitap Adı</th>
                            <th>ISBN</th>
                            <th>Barkod</th>
                            <th>Raf Konumu</th>
                            <th>Yıpranma Durumu</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($copies as $copy)
                        <tr data-copy-id="{{ $copy->id }}">
                            <td>{{ $copy->id }}</td>
                            <td>{{ $copy->book->book_name }}</td>
                            <td>{{ $copy->book->isbn }}</td>
                            <td>{{ $copy->barcode }}</td>
                            <td>{{ $copy->shelf_location ?? 'Belirtilmemiş' }}</td>
                            <td>{{ $copy->condition}}</td>
                            <td>
                                <span class="badge {{ $copy->status == 'available' ? 'bg-success' : 'bg-warning' }}">
                                     {{$copy->status}}
                                </span>
                            </td>
                            
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-info btn-sm borrow-book" 
                                        data-id="{{ $copy->id }}" 
                                        title="Ödünç Ver"
                                        {{ $copy->status != 'available' ? 'disabled' : '' }}>
                                        <i class="bi bi-journal-arrow-up"></i> Ödünç Ver
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm return-book" 
                                        data-id="{{ $copy->id }}" 
                                        title="Teslim Al"
                                        {{ $copy->status != 'borrowed' ? 'disabled' : '' }}>
                                        <i class="bi bi-journal-arrow-down"></i> Teslim Al
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm extend-date" 
                                        data-id="{{ $copy->id }}" 
                                        title="Süreyi Uzat"
                                        {{ $copy->status != 'borrowed' ? 'disabled' : '' }}>
                                        <i class="bi bi-clock-history"></i> Süreyi Uzat
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Henüz kitap kopyası eklenmemiş</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Borrow Modal -->
<div class="modal fade" id="borrowModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="borrowForm" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="selectedUserId">
                <div class="modal-header">
                    <h5 class="modal-title">Kitap Ödünç Ver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Kullanıcı Arama -->
                    <div class="mb-3">
                        <label class="form-label">Telefon Numarası ile Ara:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="userSearch" placeholder="5XX XXX XXXX">
                            <button class="btn btn-outline-secondary" type="button" id="searchUser">Ara</button>
                        </div>
                    </div>

                    <!-- Kullanıcı Bilgileri -->
                    <div id="userDetails" class="d-none">
                        <hr>
                        <h6>Kullanıcı Bilgileri:</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Ad Soyad:</dt>
                            <dd class="col-sm-8" id="userName">-</dd>
                            
                            <dt class="col-sm-4">E-posta:</dt>
                            <dd class="col-sm-8" id="userEmail">-</dd>
                            
                            <dt class="col-sm-4">Telefon:</dt>
                            <dd class="col-sm-8" id="userPhone">-</dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary" id="confirmBorrow" disabled>Onayla</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kitap İade Al</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bu kitabı iade almak istediğinizden emin misiniz?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="confirmReturn">Onayla</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="extendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Süre Uzat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Mevcut Son Teslim Tarihi: <span id="currentDueDate"></span></p>
                <div class="mb-3">
                    <label class="form-label">Eklenecek Gün Sayısı:</label>
                    <input type="number" class="form-control" id="daysToAdd" min="1" value="7">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="confirmExtend">Onayla</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCopyId = null;
    const borrowModal = new bootstrap.Modal(document.getElementById('borrowModal'));
    const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
    const extendModal = new bootstrap.Modal(document.getElementById('extendModal'));

    // Ödünç Ver butonu tıklandığında
    document.querySelectorAll('.borrow-book').forEach(button => {
        button.addEventListener('click', function() {
            currentCopyId = this.dataset.id;
            document.getElementById('borrowForm').action = `/adminpanel/copies/${currentCopyId}/borrow`;
            borrowModal.show();
        });
    });

    // Kullanıcı arama - Başarılı aramada form input'unu güncelle
    document.getElementById('searchUser').addEventListener('click', function() {
        const phone = document.getElementById('userSearch').value;
        if(!phone) {
            alert('Lütfen bir telefon numarası girin');
            return;
        }

        fetch(`/adminpanel/users/search?phone=${phone}`)
            .then(response => response.json())
            .then(data => {
                if(data.user) {
                    selectedUserId = data.user.id;
                    document.getElementById('selectedUserId').value = data.user.id;
                    document.getElementById('userName').textContent = data.user.name;
                    document.getElementById('userEmail').textContent = data.user.email;
                    document.getElementById('userPhone').textContent = data.user.tel;
                    document.getElementById('userDetails').classList.remove('d-none');
                    document.getElementById('confirmBorrow').disabled = false;
                } else {
                    alert('Kullanıcı bulunamadı');
                    selectedUserId = null;
                    document.getElementById('userDetails').classList.add('d-none');
                    document.getElementById('confirmBorrow').disabled = true;
                }
            });
    });

    // Teslim Al
    document.querySelectorAll('.return-book').forEach(button => {
        button.addEventListener('click', function() {
            currentCopyId = this.dataset.id;
            returnModal.show();
        });
    });

    // Teslim alma onayı
    document.getElementById('confirmReturn').addEventListener('click', function() {
        fetch(`/adminpanel/copies/${currentCopyId}/return`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route('admin.manageBorrowings') }}";
            } else {
                alert(data.message || 'Bir hata oluştu');
            }
        });
    });

    // Süre Uzat butonuna tıklandığında
    document.querySelectorAll('.extend-date').forEach(button => {
        button.addEventListener('click', function() {
            currentCopyId = this.dataset.id;
            // Önce modalı göster
            extendModal.show();
            // Sonra tarihi getir
            fetch(`/adminpanel/copies/${currentCopyId}/due-date`)
                .then(response => response.json())
                .then(data => {
                    console.log('Due date response:', data); // DEBUG: Gelen veriyi konsola yaz
                    if(data.due_date) {
                        document.getElementById('currentDueDate').textContent = data.due_date;
                    } else if(data.message) {
                        document.getElementById('currentDueDate').textContent = data.message;
                    } else {
                        document.getElementById('currentDueDate').textContent = 'Tarih bulunamadı';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('currentDueDate').textContent = 'Tarih yüklenirken hata oluştu';
                });
        });
    });

    // Süre uzatma işlemi
    document.getElementById('confirmExtend').addEventListener('click', function() {
        const days = parseInt(document.getElementById('daysToAdd').value) || 0;
        if(days <= 0) {
            alert('Lütfen geçerli bir gün sayısı girin');
            return;
        }

        fetch(`/adminpanel/copies/${currentCopyId}/extend`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ days: days })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route('admin.manageBorrowings') }}";
            } else {
                alert(data.message || 'Süre uzatma işlemi başarısız oldu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu');
        });
    });
});
</script>
@endsection
