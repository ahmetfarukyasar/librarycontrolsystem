@extends('layout')

@section('title', 'Profilim')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('avatars/' . ($user->avatar ?? 'default-avatar.png')) }}" class="rounded-circle mb-3" alt="Profil Resmi" width="150" height="150">
                                <label for="avatar" class="position-absolute bottom-0 end-0 translate-middle p-2 bg-primary rounded-circle" style="cursor: pointer;">
                                    <i class="bi bi-pencil text-white"></i>
                                </label>
                            </div>
                            <form action="/profile/avatar" method="POST" enctype="multipart/form-data" style="display: none;">
                                @csrf
                                <input type="file" class="form-control" name="avatar" id="avatar" required onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="col-md-9">
                            <h3>{{ $user->name }}</h3>
                            <p class="text-muted mb-2"><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                            <p class="text-muted mb-2"><i class="bi bi-telephone"></i> {{ $user->tel }}</p>
                            <p class="text-muted mb-0"><i class="bi bi-person-badge"></i> {{ $user->isAdmin() ? 'Yönetici' : 'Üye' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#borrowed" role="tab">Ödünç Alınan Kitaplar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#reviews" role="tab">Yorumlarım</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#ratings" role="tab">Değerlendirmelerim</a>
                        </li>
                    </ul>

                    <div class="tab-content pt-4">
                        <div class="tab-pane active" id="borrowed" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Kitap</th>
                                            <th>Barkod</th>
                                            <th>Alış Tarihi</th>
                                            <th>İade Tarihi</th>
                                            <th>Durum</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->borrowings as $borrow)
                                        <tr>
                                            <td>{{ $borrow->copy->book->book_name }}</td>
                                            <td>{{ $borrow->copy->barcode }}</td>
                                            <td>{{ $borrow->formatted_purchase_date }}</td>
                                            <td>{{ $borrow->formatted_return_date }}</td>
                                            <td>
                                                <span class="badge bg-{{ $borrow->status_color }}">
                                                    {{ $borrow->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($borrow->status === 'borrowed')
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#extendModal{{ $borrow->id }}">
                                                        <i class="bi bi-calendar-plus"></i> Süre Uzat
                                                    </button>

                                                    <!-- Süre Uzatma Modalı -->
                                                    <div class="modal fade" id="extendModal{{ $borrow->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('user.extendBorrowDate', $borrow->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Süre Uzatma İsteği</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Mevcut İade Tarihi: {{ $borrow->formatted_return_date }}</p>
                                                                        <p class="text-muted">Not: İade süresi 7 gün uzatılacaktır. Bir kitabın süresini en fazla 3 kez uzatabilirsiniz.</p>
                                                                        <p class="text-muted">Kalan Uzatma Hakkı: {{ 3 - ($borrow->extension_count ?? 0) }}</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                                                        <button type="submit" class="btn btn-primary">Uzatma İste</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="reviews" role="tabpanel">
                            @foreach($user->reviews as $review)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $review->book->book_name }}</h5>
                                    <p class="card-text">{{ $review->comment }}</p>
                                    <small class="text-muted">{{ $review->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="tab-pane" id="ratings" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Kitap</th>
                                            <th>Puan</th>
                                            <th>Tarih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->ratings as $rating)
                                        <tr>
                                            <td>{{ $rating->book->book_name }}</td>
                                            <td>{{ $rating->rating }}</td>
                                            <td>{{ $rating->created_at->format('d.m.Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
@endpush
