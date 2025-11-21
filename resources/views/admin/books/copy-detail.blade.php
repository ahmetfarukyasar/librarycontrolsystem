@extends('layout')

@section('title', 'Kitap Kopyası Detay')

@section('content')
<div class="container">
    <h2 class="mb-4">Kitap Kopyası Detay</h2>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Kitap Bilgileri</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Kitap Adı:</dt>
                <dd class="col-sm-9">{{ $copy->book->book_name }}</dd>

                <dt class="col-sm-3">ISBN:</dt>
                <dd class="col-sm-9">{{ $copy->book->isbn }}</dd>

                <dt class="col-sm-3">Kopya Durumu:</dt>
                <dd class="col-sm-9">
                    <span class="badge {{ $copy->status == 'available' ? 'bg-success' : 'bg-warning' }}">
                        {{ $copy->status }}
                    </span>
                </dd>

                <dt class="col-sm-3">Yıpranma Durumu:</dt>
                <dd class="col-sm-9">{{ $copy->condition }}</dd>

                <dt class="col-sm-3">Raf Konumu:</dt>
                <dd class="col-sm-9">
                    @if($copy->shelf_location)
                        Blok: {{ $copy->locationDetails['block'] }},
                        Kat: {{ $copy->locationDetails['floor'] }},
                        Sıra: {{ $copy->locationDetails['row'] }},
                        Raf: {{ $copy->locationDetails['shelf'] }},
                        Pozisyon: {{ $copy->locationDetails['position'] }}
                    @else
                        -
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Edinme Bilgileri</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Edinme Türü:</dt>
                <dd class="col-sm-9">{{ $copy->acquisition->source->name ?? '-' }}</dd>

                <dt class="col-sm-3">Edinme Tarihi:</dt>
                <dd class="col-sm-9">{{ $copy->acquisition->acquisition_date ? date('d/m/Y', strtotime($copy->acquisition->acquisition_date)) : '-' }}</dd>

                <dt class="col-sm-3">Maliyet:</dt>
                <dd class="col-sm-9">{{ $copy->acquisition->acquisition_cost ? number_format($copy->acquisition->acquisition_cost, 2) . ' ₺' : '-' }}</dd>

                <dt class="col-sm-3">Edinme Yeri:</dt>
                <dd class="col-sm-9">{{ $copy->acquisition->acquisition_place ?? '-' }}</dd>

                <dt class="col-sm-3">Fatura No:</dt>
                <dd class="col-sm-9">{{ $copy->acquisition->acquisition_invoice ?? '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="btn-group">
        <a href="{{ route('admin.editCopy', $copy->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Düzenle
        </a>
        <a href="{{ route('admin.manageCopies') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Geri
        </a>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
@endsection
