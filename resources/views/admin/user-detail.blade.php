@extends('layout')

@section('title', 'Kullanıcı Detayı')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ $user->name }}</h3>
                        <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-primary' }}">
                            {{ $user->isAdmin() ? 'Admin' : 'Üye' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="{{ asset('avatars/' . ($user->avatar ?? 'default-avatar.png')) }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Kişisel Bilgiler</h5>
                                    <p><strong>TC Kimlik No:</strong> {{ $user->tcno }}</p>
                                    <p><strong>E-posta:</strong> {{ $user->email }}</p>
                                    <p><strong>Telefon:</strong> {{ $user->tel }}</p>
                                    <p><strong>Kayıt Tarihi:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Özet Bilgiler</h5>
                                    <p><strong>Toplam Ödünç Alma:</strong> {{ $user->borrowings->count() }}</p>
                                    <p><strong>Aktif Ödünç:</strong> {{ $user->borrowings->where('status', 'borrowed')->count() }}</p>
                                    <p><strong>Gecikmiş Teslim:</strong> {{ $user->borrowings->where('status', 'overdue')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktif Ödünç Alınan Kitaplar -->
        <div class="col-md-12 mb-4">
            <!-- Yeni Kitap Ödünç Verme Kartı -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0">Yeni Kitap Ödünç Ver</h6>
                </div>
                <div class="card-body py-3">
                    <form action="{{ route('admin.checkBarcode', $user->id) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="barcode" name="barcode" 
                                       value="{{ old('barcode') }}" placeholder="Barkod No" required>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-search"></i> Ara
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control form-control-sm" id="return_date" 
                                   name="return_date" value="{{ old('return_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="action" value="borrow" 
                                    class="btn btn-primary btn-sm w-100" 
                                    {{ session('book_error') ? 'disabled' : '' }}>
                                <i class="bi bi-plus-lg"></i> Ödünç Ver
                            </button>
                        </div>
                        
                        @if(session('book_details'))
                            <div class="col-12 mt-2">
                                <div class="d-flex gap-3">
                                    <div><strong>Kitap:</strong> {{ session('book_details.name') }}</div>
                                    <div><strong>Yazar:</strong> {{ session('book_details.author') }}</div>
                                    <div><strong>ISBN:</strong> {{ session('book_details.isbn') }}</div>
                                    <div><strong>Yayınevi:</strong> {{ session('book_details.publisher') }}</div>
                                    <div><strong>Sayfa:</strong> {{ session('book_details.pages') }}</div>
                                </div>
                            </div>
                        @endif
                        @if(session('book_error'))
                            <div class="col-12 mt-2">
                                <span class="text-danger">{{ session('book_error') }}</span>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Aktif Ödünç Tablosu -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Aktif Ödünç Alınan Kitaplar</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kitap</th>
                                    <th>Alış Tarihi</th>
                                    <th>Son Teslim</th>
                                    <th>Durum</th>
                                    <th>Kalan Gün</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->borrowings->where('status', 'borrowed') as $borrow)
                                <tr>
                                    <td>{{ $borrow->copy->book->book_name }}</td>
                                    <td>{{ $borrow->purchase_date->format('d/m/Y') }}</td>
                                    <td>{{ $borrow->return_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $borrow->status_color }}">
                                            {{ $borrow->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $borrow->days_remaining < 0 ? 'bg-danger bg-opacity-25' : 'bg-secondary' }}">
                                            {{ (int)$borrow->days_remaining }} gün
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <form action="{{ route('admin.returnBook', $borrow->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Kitabı teslim almak istediğinize emin misiniz?')">
                                                    <i class="bi bi-check-lg"></i> Teslim Al
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal{{ $borrow->id }}">
                                                <i class="bi bi-info-circle"></i> Detaylar
                                            </button>

                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#extendModal{{ $borrow->id }}">
                                                <i class="bi bi-calendar-plus"></i> Süre Uzat
                                            </button>
                                        </div>

                                        <!-- Detaylar Modalı -->
                                        <div class="modal fade" id="detailsModal{{ $borrow->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ödünç Detayları</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <h6>Kitap Bilgileri</h6>
                                                            <p class="mb-1"><strong>Kitap Adı:</strong> {{ $borrow->copy->book->book_name }}</p>
                                                            <p class="mb-1"><strong>Yazar:</strong> {{ $borrow->copy->book->author->name }}</p>
                                                            <p class="mb-1"><strong>ISBN:</strong> {{ $borrow->copy->book->isbn }}</p>
                                                            <p class="mb-1"><strong>Barkod:</strong> {{ $borrow->copy->barcode }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h6>Ödünç Bilgileri</h6>
                                                            <p class="mb-1"><strong>Alış Tarihi:</strong> {{ $borrow->purchase_date->format('d.m.Y H:i') }}</p>
                                                            <p class="mb-1"><strong>Son Teslim:</strong> {{ $borrow->return_date->format('d.m.Y H:i') }}</p>
                                                            <p class="mb-1"><strong>Kalan Gün:</strong> {{ $borrow->days_remaining }} gün</p>
                                                            <p class="mb-1"><strong>Yapılan Uzatma:</strong> {{ $borrow->extension_count ?? 0 }}/3</p>
                                                            @if($borrow->delay_day > 0)
                                                                <p class="mb-1 text-danger">
                                                                    <strong>Gecikme:</strong> {{ $borrow->delay_day }} gün
                                                                    @if($borrow->late_fee)
                                                                        (Ceza: {{ number_format($borrow->late_fee, 2) }} ₺)
                                                                    @endif
                                                                </p>
                                                            @endif
                                                        </div>
                                                        @if($borrow->notes)
                                                            <div class="mb-3">
                                                                <h6>Notlar</h6>
                                                                <p class="mb-0">{{ $borrow->notes }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Süre Uzatma Modalı -->
                                        <div class="modal fade" id="extendModal{{ $borrow->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.extendDueDate', $borrow->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Süre Uzat</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Kaç Gün Uzatılacak?</label>
                                                                <input type="number" name="days" class="form-control" 
                                                                       value="7" min="1" max="30" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" 
                                                                    data-bs-dismiss="modal">İptal</button>
                                                            <button type="submit" class="btn btn-primary">Uzat</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aktif ödünç alınan kitap bulunmamaktadır.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rezerve Edilmiş Kitaplar -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Rezerve Edilmiş Kitaplar</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($activeReservations) && $activeReservations->count())
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>Kitap</th>
                                            <th>Barkod</th>
                                            <th>Rezervasyon Onay Tarihi</th>
                                            <th>Kalan Gün</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activeReservations as $reservation)
                                            <tr>
                                                <td>{{ $reservation->bookCopy->book->book_name }}</td>
                                                <td>{{ $reservation->bookCopy->barcode }}</td>
                                                <td>{{ \Carbon\Carbon::parse($reservation->approval_date)->format('d.m.Y H:i') }}</td>
                                                <td>
                                                    @php
                                                        $daysLeft = $reservation->days_left;
                                                    @endphp
                                                    @if($daysLeft >= 0)
                                                        <span class="badge bg-info">{{ (int)$daysLeft }} gün</span>
                                                    @else
                                                        <span class="badge bg-danger">Süre doldu</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="alert alert-warning mt-2">
                                    <strong>Not:</strong> Rezerve edilen kitaplar 3 gün içinde teslim alınmazsa rezervasyon otomatik olarak iptal edilir.
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                Aktif rezerve edilmiş kitap bulunmamaktadır.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Geçmiş Ödünç Kayıtları -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Ödünç Alma Geçmişi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kitap</th>
                                    <th>Alış Tarihi</th>
                                    <th>Teslim Tarihi</th>
                                    <th>Durum</th>
                                    <th>Gecikme Durumu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->borrowings->where('status', 'returned') as $borrow)
                                <tr>
                                    <td>{{ $borrow->copy->book->book_name }}</td>
                                    <td>{{ $borrow->purchase_date->format('d/m/Y') }}</td>
                                    <td>{{ $borrow->returned_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $borrow->status_color }}">
                                            {{ $borrow->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($borrow->delay_day > 0)
                                            <span class="text-danger">{{ $borrow->delay_day }} gün gecikme</span>
                                        @else
                                            <span class="text-success">Zamanında teslim</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Geçmiş ödünç kayıtları bulunmamaktadır.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
@endpush
@endsection
