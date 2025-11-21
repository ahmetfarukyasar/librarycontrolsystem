@extends('layout')

@section('title', 'Kitap Listesi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Kitap Listesi</h2>
            <a href="{{ url('/adminpanel/create-book') }}" class="btn btn-primary">Yeni Kitap Ekle</a>
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
                            <th>Yazar</th>
                            <th>Dil</th>
                            <th>Sayfa</th>
                            <th>Kategori</th>
                            <th>Yayınevi</th>
                            <th>Yayın Yılı</th>
                           
                           
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                        <tr data-book-id="{{ $book->id }}">
                            <td>{{ $book->id }}</td>
                            <td>{{ $book->book_name }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>{{ $book->author->name }}</td>
                            <td>{{ $book->language->name }}</td>
                            <td>{{ $book->page_count }}</td>
                            <td>{{ $book->category->category_name }}</td>
                            <td>{{ $book->publisher->name }}</td>
                            <td>{{ $book->publish_year }}</td>
                            
                           
                            <td>
                                <div class="btn-group" role="group">
                                    
                                    <a href="{{ route('admin.editBook', $book->id) }}" class="btn btn-warning btn-sm" title="Düzenle">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-book" data-id="{{ $book->id }}" title="Sil">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">Henüz kitap eklenmemiş</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kitap Silme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bu kitabı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Kitap Kopyası Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStockForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="barcode" class="form-label">Barkod*</label>
                            <input type="text" class="form-control" id="barcode" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="shelf_location" class="form-label">Raf Konumu</label>
                            <input type="text" class="form-control" id="shelf_location">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="condition" class="form-label">Durum</label>
                            <select class="form-select" id="condition">
                                <option value="Yeni">Yeni</option>
                                <option value="İyi">İyi</option>
                                <option value="Orta">Orta</option>
                                <option value="Kötü">Kötü</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="acquisition_date" class="form-label">Edinme Tarihi</label>
                            <input type="date" class="form-control" id="acquisition_date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_reference">
                                <label class="form-check-label" for="is_reference">
                                    Başvuru Kitabı
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control" id="notes" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="saveBookCopy">Kaydet</button>
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
    let currentBookId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    // Silme butonuna tıklandığında
    const deleteButtons = document.querySelectorAll('.delete-book');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentBookId = this.dataset.id;
            deleteModal.show();
        });
    });

    // Silme işlemini onayla
    document.getElementById('confirmDelete').addEventListener('click', function() {
        fetch(`/adminpanel/delete-book/${currentBookId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-book-id="${currentBookId}"]`).remove();
                deleteModal.hide();
                alert('Kitap başarıyla silindi.');
            } else {
                alert(data.message || 'Silme işlemi başarısız oldu.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu.');
        });
    });

    // Durum değiştirme işlemi için
    const statusButtons = document.querySelectorAll('.status-change');
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.dataset.id;
            fetch(`/adminpanel/books/${bookId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusBadge = this.closest('tr').querySelector('.badge');
                    if (data.newStatus === 'available') {
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Mevcut';
                    } else {
                        statusBadge.className = 'badge bg-secondary';
                        statusBadge.textContent = 'Bakımda';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Bir hata oluştu.');
            });
        });
    });

    // Stok ekleme modalı için
    const addStockModal = new bootstrap.Modal(document.getElementById('addStockModal'));

    // Stok Ekle butonuna tıklandığında
    document.querySelectorAll('a[href*="/adminpanel/addBook/"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentBookId = this.href.split('/').pop();
            addStockModal.show();
        });
    });

    // Kitap kopyası kaydetme
    document.getElementById('saveBookCopy').addEventListener('click', function() {
        const formData = {
            book_id: currentBookId,
            barcode: document.getElementById('barcode').value,
            shelf_location: document.getElementById('shelf_location').value,
            condition: document.getElementById('condition').value,
            is_reference: document.getElementById('is_reference').checked,
            acquisition_date: document.getElementById('acquisition_date').value,
            notes: document.getElementById('notes').value
        };

        fetch('/adminpanel/books/add-copy', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addStockModal.hide();
                document.getElementById('addStockForm').reset();
                alert('Kitap kopyası başarıyla eklendi.');
                // Sayfayı yenile veya tabloyu güncelle
                location.reload();
            } else {
                alert(data.message || 'Bir hata oluştu.');
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