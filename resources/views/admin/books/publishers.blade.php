@extends('layout')

@section('title', 'Yayınevi Listesi')

@section('content')
<div class="container">

    <div class="row mb-4">
        <div class="col">
        <h2 class="mb-4">Yayınevi Listesi</h2>
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#newPublisherCard" aria-expanded="false" aria-controls="newPublisherCard">
                        <i class="bi bi-plus-circle"></i> Yeni Yayınevi Ekle
                    </button>
                </div>
                <div class="collapse" id="newPublisherCard">
                    <div class="card-body">
                        <form action="{{ route('admin.createPublisher') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="publisher_name" class="form-label">Yayınevi Adı</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Adres</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Yayınevi Ekle
                            </button>
                        </form>
                    </div>
                </div>
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
                            <th>Yayınevi Adı</th>
                            <th>İletişim</th>
                            <th>Website</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($publishers as $publisher)
                        <tr>
                            <td>{{ $publisher->id }}</td>
                            <td>{{ $publisher->name }}</td>
                            <td>
                                <small class="d-block">{{ $publisher->phone }}</small>
                                <small class="d-block">{{ $publisher->email }}</small>
                            </td>
                            <td>
                                @if($publisher->website)
                                    <a href="{{ $publisher->website }}" target="_blank">{{ $publisher->website }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    
                                    <button type="button" class="btn btn-danger btn-sm delete-publisher" data-id="{{ $publisher->id }}" title="Sil">
                                        <i class="bi bi-trash"></i> Sil
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Henüz yayınevi eklenmemiş</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
    const deleteButtons = document.querySelectorAll('.delete-publisher');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const publisherId = this.dataset.id;
            if(confirm('Bu yayınevini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
                fetch(`/adminpanel/publishers/${publisherId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        this.closest('tr').remove();
                    } else {
                        alert('Bu yayınevi silinemiyor çünkü bağlı kitaplar var.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Silme işlemi başarısız oldu.');
                });
            }
        });
    });
});
</script>
@endsection