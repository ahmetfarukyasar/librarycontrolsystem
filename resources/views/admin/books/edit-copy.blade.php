@extends('layout')

@section('title', 'Kitap Kopyası Düzenle')

@section('content')
<div class="container">
    <h2 class="mb-4">Kitap Kopyası Düzenle</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.updateCopy', $copy->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Kitap Adı</label>
                <input type="text" class="form-control" value="{{ $copy->book->book_name }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" class="form-control" value="{{ $copy->book->isbn }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label for="condition" class="form-label">Yıpranma Durumu</label>
                <select class="form-select" id="condition" name="condition" required>
                    <option value="yıpranmamış" {{ $copy->condition == 'yıpranmamış' ? 'selected' : '' }}>Yıpranmamış</option>
                    <option value="az yıpranmış" {{ $copy->condition == 'az yıpranmış' ? 'selected' : '' }}>Az Yıpranmış</option>
                    <option value="yıpranmış" {{ $copy->condition == 'yıpranmış' ? 'selected' : '' }}>Yıpranmış</option>
                    <option value="çok yıpranmış" {{ $copy->condition == 'çok yıpranmış' ? 'selected' : '' }}>Çok Yıpranmış</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Durum</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="available" {{ $copy->status == 'available' ? 'selected' : '' }}>Mevcut</option>
                    <option value="borrowed" {{ $copy->status == 'borrowed' ? 'selected' : '' }}>Ödünç Verildi</option>
                    <option value="reserved" {{ $copy->status == 'reserved' ? 'selected' : '' }}>Rezerve</option>
                    <option value="lost" {{ $copy->status == 'lost' ? 'selected' : '' }}>Kayıp</option>
                </select>
            </div>

            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Raf Konumu</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="block" class="form-label">Blok</label>
                                <select class="form-select" name="block" required>
                                    <option value="A" {{ optional($copy->shelfLocation)->block == 'A' ? 'selected' : '' }}>A Blok</option>
                                    <option value="B" {{ optional($copy->shelfLocation)->block == 'B' ? 'selected' : '' }}>B Blok</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="floor" class="form-label">Kat</label>
                                <select class="form-select" name="floor" required>
                                    @for($i = 0; $i <= 2; $i++)
                                        <option value="{{ $i }}" {{ optional($copy->shelfLocation)->floor == $i ? 'selected' : '' }}>{{ $i }}. Kat</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="row" class="form-label">Sıra</label>
                                <select class="form-select" name="row" required>
                                    @for($i = 1; $i <= 21; $i++)
                                        <option value="{{ $i }}" {{ optional($copy->shelfLocation)->row == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="shelf" class="form-label">Raf</label>
                                <select class="form-select" name="shelf" required>
                                    @for($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}" {{ optional($copy->shelfLocation)->shelf == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="position" class="form-label">Pozisyon</label>
                                <input type="number" class="form-control" name="position" 
                                       value="{{ optional($copy->shelfLocation)->position }}"
                                       min="1" max="150" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Edinme Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="acquisition_source_id" class="form-label">Edinme Türü</label>
                                <select class="form-select" name="acquisition_source_id" required>
                                    @foreach($acquisitionSources as $source)
                                        <option value="{{ $source->id }}" 
                                            {{ $copy->acquisition->acquisition_source_id == $source->id ? 'selected' : '' }}>
                                            {{ $source->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="acquisition_date" class="form-label">Edinme Tarihi</label>
                                <input type="date" class="form-control" name="acquisition_date" 
                                       value="{{ $copy->acquisition->acquisition_date }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="acquisition_cost" class="form-label">Maliyet (₺)</label>
                                <input type="number" step="0.01" class="form-control" name="acquisition_cost" 
                                       value="{{ $copy->acquisition->acquisition_cost }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="acquisition_place" class="form-label">Edinme Yeri</label>
                                <input type="text" class="form-control" name="acquisition_place" 
                                       value="{{ $copy->acquisition->acquisition_place }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="acquisition_invoice" class="form-label">Fatura No</label>
                                <input type="text" class="form-control" name="acquisition_invoice" 
                                       value="{{ $copy->acquisition->acquisition_invoice }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.manageCopies') }}" class="btn btn-secondary">İptal</a>
            </div>
        </div>
    </form>
</div>
@endsection
