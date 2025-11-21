@extends('layout')

@section('title', 'Kitap Kopyalarını Yönet')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Kitap Kopyaları Listesi</h2>
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ url('/adminpanel/create-copy') }}" class="btn btn-primary">Yeni Kitap Kopyası Ekle</a>
                <form class="d-flex gap-2" method="GET" action="{{ route('admin.manageCopies') }}">
                    <input type="text" name="search" class="form-control" placeholder="Arama..." value="{{ request('search') }}">
                    <select name="search_type" class="form-select" style="width:auto;">
                        <option value="all" {{ request('search_type', 'all') == 'all' ? 'selected' : '' }}>Tümü</option>
                        <option value="book_name" {{ request('search_type') == 'book_name' ? 'selected' : '' }}>Kitap Adı</option>
                        <option value="isbn" {{ request('search_type') == 'isbn' ? 'selected' : '' }}>ISBN</option>
                        <option value="barcode" {{ request('search_type') == 'barcode' ? 'selected' : '' }}>Barkod</option>
                        <option value="acquisition_source" {{ request('search_type') == 'acquisition_source' ? 'selected' : '' }}>Edinme Türü</option>
                        <option value="shelf_location" {{ request('search_type') == 'shelf_location' ? 'selected' : '' }}>Yer Bilgisi</option>
                    </select>
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
                            <th class="location-column">Yer Bilgisi</th>
                            <th>Edinme Türü</th>
                            <th>Edinme Tarihi</th>
                            <th>Maliyet</th>
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
                            <td class="location-column">{{ $copy->shelf_location ?? 'Belirtilmemiş' }}</td>
                            <td>{{ $copy->acquisition && $copy->acquisition->source ? $copy->acquisition->source->name : 'Belirtilmemiş' }}</td>
                            <td>{{ $copy->acquisition && $copy->acquisition->acquisition_date ? date('d/m/Y', strtotime($copy->acquisition->acquisition_date)) : 'Belirtilmemiş' }}</td>
                            <td>{{ $copy->acquisition && $copy->acquisition->acquisition_cost ? number_format($copy->acquisition->acquisition_cost, 2) . ' ₺' : 'Belirtilmemiş' }}</td>
                            <td>{{ $copy->condition}}</td>
                            <td>
                                <span class="badge 
                                    {{ $copy->status == 'available' ? 'bg-success' : ($copy->status == 'borrowed' ? 'bg-warning' : ($copy->status == 'reserved' ? 'bg-info' : 'bg-secondary')) }}">
                                    @if($copy->status == 'available')
                                        Mevcut
                                    @elseif($copy->status == 'borrowed')
                                        Ödünç Verildi
                                    @elseif($copy->status == 'reserved')
                                        Rezerve
                                    @elseif($copy->status == 'lost')
                                        Kayıp
                                    @else
                                        {{ ucfirst($copy->status) }}
                                    @endif
                                </span>
                            </td>
                            
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ url('/adminpanel/copies/'.$copy->id) }}" class="btn btn-info btn-sm" title="Detay">
                                        <i class="bi bi-eye"></i> Detay
                                    </a>
                                    <a href="{{ url('/adminpanel/edit-copy/'.$copy->id) }}" class="btn btn-warning btn-sm" title="Düzenle">
                                        <i class="bi bi-pencil"></i> Düzenle
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-copy" data-id="{{ $copy->id }}" title="Sil">
                                        <i class="bi bi-trash"></i> Sil
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Kitap Kopyası Silme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bu kitap kopyasını silmek istediğinizden emin misiniz?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Sil</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<style>
    .table th.location-column,
    .table td.location-column {
        min-width: 150px;
    }
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCopyId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    // Silme butonuna tıklandığında
    const deleteButtons = document.querySelectorAll('.delete-copy');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentCopyId = this.dataset.id;
            deleteModal.show();
        });
    });

    // Silme işlemini onayla
    document.getElementById('confirmDelete').addEventListener('click', function() {
        fetch(`/adminpanel/copies/${currentCopyId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-copy-id="${currentCopyId}"]`).remove();
                deleteModal.hide();
                alert('Kitap kopyası başarıyla silindi.');
            } else {
                alert('Silme işlemi sırasında bir hata oluştu.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu.');
        });
    });
});
</script>
@endsection