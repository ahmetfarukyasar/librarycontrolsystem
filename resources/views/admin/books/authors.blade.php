@extends('layout')

@section('title', 'Yazar Listesi')

@section('content')
<div class="container">

    <div class="row mb-4">
        <div class="col">
        <h2 class="mb-4">Yazar Listesi</h2>
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#newAuthorCard" aria-expanded="false" aria-controls="newAuthorCard">
                        <i class="bi bi-plus-circle"></i> Yeni Yazar Ekle
                    </button>
                </div>
                <div class="collapse" id="newAuthorCard">
                    <div class="card-body">
                        <form action="{{ route('admin.createAuthor') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="author_name" class="form-label">Yazar Adı</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="author_description" class="form-label">Yazar Hakkında</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Yazar Ekle
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
                            <th>Yazar Adı</th>
                            <th>Açıklama</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($authors as $author)
                        <tr>
                            <td>{{ $author->id }}</td>
                            <td>{{ $author->name }}</td>
                            <td>{{ Str::limit($author->description, 100) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    
                                    <button type="button" class="btn btn-danger btn-sm delete-author" data-id="{{ $author->id }}" title="Sil">
                                        <i class="bi bi-trash"></i> Sil
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Henüz yazar eklenmemiş</td>
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
    const deleteButtons = document.querySelectorAll('.delete-author');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const authorId = this.dataset.id;
            if(confirm('Bu yazarı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
                fetch(`/adminpanel/authors/${authorId}`, {
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
                        alert('Bu yazar silinemiyor çünkü bağlı kitaplar var.');
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