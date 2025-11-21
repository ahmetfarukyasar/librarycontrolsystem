@extends('layout')

@section('title', 'Edinme İşlemleri Yönetimi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Yeni Edinme Türü Ekle</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.storeAcquisitionSource') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Edinme Türü Adı</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <button type="submit" class="btn btn-primary">Ekle</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Mevcut Edinme Türleri</h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($sources as $source)
                            <div class="list-group-item">
                                <h6 class="mb-1">{{ $source->name }}</h6>
                                @if($source->description)
                                    <small class="text-muted">{{ $source->description }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tüm Edinme İşlemleri</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kitap Adı</th>
                                    <th>ISBN</th>
                                    <th>Barkod</th>
                                    <th>Edinme Türü</th>
                                    <th>Edinme Tarihi</th>
                                    <th>Maliyet</th>
                                    <th>Yer</th>
                                    <th>Fatura No</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acquisitions as $acquisition)
                                    <tr>
                                        <td>{{ $acquisition->id }}</td>
                                        <td>{{ $acquisition->bookCopy->book->book_name }}</td>
                                        <td>{{ $acquisition->bookCopy->book->isbn }}</td>
                                        <td>{{ $acquisition->bookCopy->barcode }}</td>
                                        <td>{{ $acquisition->source->name }}</td>
                                        <td>{{ $acquisition->acquisition_date ? date('d/m/Y', strtotime($acquisition->acquisition_date)) : '-' }}</td>
                                        <td>{{ $acquisition->acquisition_cost ? number_format($acquisition->acquisition_cost, 2) . ' ₺' : '-' }}</td>
                                        <td>{{ $acquisition->acquisition_place ?? '-' }}</td>
                                        <td>{{ $acquisition->acquisition_invoice ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $acquisitions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.list-group-item {
    border-left: 3px solid #007bff;
}
</style>
@endsection
