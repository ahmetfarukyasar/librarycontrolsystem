@extends('layout')

@section('title', 'Üye Listesi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Üye Listesi</h2>
            <a href="{{ route('admin.createUser') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Yeni Üye Ekle
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TC Kimlik No</th>
                            <th>Ad Soyad</th>
                            <th>E-posta</th>
                            <th>Telefon</th>
                            <th>Admin</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->tcno }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->tel)
                                    {{ $user->tel }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $user->is_admin ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->is_admin ? 'Evet' : 'Hayır' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.editUser', $user->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Henüz üye bulunmuyor</td>
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
    const deleteButtons = document.querySelectorAll('.delete-user');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if(confirm('Bu üyeyi silmek istediğinizden emin misiniz?')) {
                const userId = this.dataset.id;
                
                fetch(`/adminpanel/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        this.closest('tr').remove();
                    } else {
                        alert('Silme işlemi başarısız oldu!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Bir hata oluştu!');
                });
            }
        });
    });
});
</script>
@endsection
