@extends('layout')

@section('title', 'Yeni Kitap Kopyası Ekle')

@section('content')
<div class="container">
    <h2 class="mb-4">Yeni Kitap Kopyası Ekle</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.storeCopy') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="book_search" class="form-label">Kitap</label>
                <input type="text" class="form-control" id="book_search" name="book_search" placeholder="Kitap ismi girin..." autocomplete="off" required>
                <input type="hidden" id="selected_book_id" name="book_id">
                <div id="book_search_list" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="isbn_input" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn_input" name="isbn_input" placeholder="ISBN girin..." autocomplete="off">
            </div>

            <div class="col-md-6 mb-3">
                <label for="publisher" class="form-label">Yayınevi</label>
                <input type="text" class="form-control" id="publisher" name="publisher" readonly>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="acquisition_source_id" class="form-label">Edinme Türü</label>
                    <select class="form-select" name="acquisition_source_id" required>
                        <option value="">Seçiniz</option>
                        @foreach($acquisitionSources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="acquisition_date" class="form-label">Edinme Tarihi</label>
                    <input type="date" class="form-control" name="acquisition_date" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="acquisition_cost" class="form-label">Edinme Maliyeti</label>
                    <input type="number" class="form-control" name="acquisition_cost" step="0.01">
                </div>
                <div class="col-md-4">
                    <label for="acquisition_place" class="form-label">Edinme Yeri</label>
                    <input type="text" class="form-control" name="acquisition_place">
                </div>
                <div class="col-md-4">
                    <label for="acquisition_invoice" class="form-label">Fatura No</label>
                    <input type="text" class="form-control" name="acquisition_invoice">
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="condition" class="form-label">Yıpranma Durumu</label>
                <select class="form-select" id="condition" name="condition" required>
                    <option value="yıpranmamış">Yıpranmamış</option>
                    <option value="az yıpranmış">Az Yıpranmış</option>
                    <option value="yıpranmış">Yıpranmış</option>
                    <option value="çok yıpranmış">Çok Yıpranmış</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Yer Bilgisi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="block" class="form-label">Blok</label>
                                <select class="form-select" id="block" name="block" required>
                                    <option value="">Blok Seçin</option>
                                    <option value="A">A Blok</option>
                                    <option value="B">B Blok</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="floor" class="form-label">Kat</label>
                                <select class="form-select" id="floor" name="floor" required>
                                    <option value="">Kat Seçin</option>
                                    <option value="0">Zemin Kat</option>
                                    <option value="1">1. Kat</option>
                                    <option value="2">2. Kat</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="row" class="form-label">Sıra No</label>
                                <select class="form-select" id="row" name="row" required>
                                    <option value="">Sıra Seçin</option>
                                    @for($i = 1; $i <= 21; $i++)
                                        <option value="{{ $i }}">{{ $i }}. Sıra</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shelf" class="form-label">Raf No</label>
                                <select class="form-select" id="shelf" name="shelf" required>
                                    <option value="">Raf Seçin</option>
                                    @for($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}">{{ $i }}. Raf</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="position" class="form-label">Pozisyon</label>
                                <input type="number" class="form-control" id="position" name="position" 
                                       min="1" max="150" required 
                                       oninput="this.value = this.value > 150 ? 150 : Math.abs(this.value)">
                                <small class="form-text text-muted">1-150 arası bir değer girin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Ekle</button>
    </form>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookSearchInput = document.getElementById('book_search');
    const bookSearchList = document.getElementById('book_search_list');
    const isbnInput = document.getElementById('isbn_input');
    const publisherInput = document.getElementById('publisher');
    const selectedBookIdInput = document.getElementById('selected_book_id');

    let debounceTimeout = null;

    // Kitap ismine göre arama
    bookSearchInput.addEventListener('input', function() {
        const query = this.value.trim();
        selectedBookIdInput.value = '';
        isbnInput.value = '';
        publisherInput.value = '';
        if (debounceTimeout) clearTimeout(debounceTimeout);
        if (query.length < 2) {
            bookSearchList.innerHTML = '';
            bookSearchList.style.display = 'none';
            return;
        }
        debounceTimeout = setTimeout(() => {
            fetch(`/api/books/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    bookSearchList.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(book => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'list-group-item list-group-item-action';
                            item.textContent = book.book_name
                                + (book.author ? ' - ' + book.author : '')
                                + (book.isbn ? ' - ' + book.isbn : '');
                            item.dataset.id = book.id;
                            item.dataset.name = book.book_name;
                            bookSearchList.appendChild(item);
                        });
                        bookSearchList.style.display = 'block';
                    } else {
                        bookSearchList.style.display = 'none';
                    }
                });
        }, 250);
    });

    // Kitap seçildiğinde
    bookSearchList.addEventListener('click', function(e) {
        if (e.target && e.target.matches('.list-group-item')) {
            bookSearchInput.value = e.target.dataset.name;
            selectedBookIdInput.value = e.target.dataset.id;
            bookSearchList.innerHTML = '';
            bookSearchList.style.display = 'none';
            // Seçilen kitap id'siyle kitap bilgilerini getir
            fetch(`/api/books/search-by-id?id=${encodeURIComponent(e.target.dataset.id)}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.book) {
                        isbnInput.value = data.book.isbn || '';
                        publisherInput.value = data.book.publisher || '';
                    } else {
                        isbnInput.value = '';
                        publisherInput.value = '';
                    }
                });
        }
    });

    // ISBN input ile kitap bulma
    isbnInput.addEventListener('input', function() {
        const isbn = this.value.trim();
        if (isbn.length < 5) {
            bookSearchInput.value = '';
            selectedBookIdInput.value = '';
            publisherInput.value = '';
            return;
        }
        fetch(`/api/books/search-by-isbn?isbn=${encodeURIComponent(isbn)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.book) {
                    bookSearchInput.value = data.book.book_name || '';
                    selectedBookIdInput.value = data.book.id || '';
                    publisherInput.value = data.book.publisher || '';
                } else {
                    bookSearchInput.value = '';
                    selectedBookIdInput.value = '';
                    publisherInput.value = '';
                }
            });
    });

    document.addEventListener('click', function(e) {
        if (!bookSearchInput.contains(e.target) && !bookSearchList.contains(e.target)) {
            bookSearchList.innerHTML = '';
            bookSearchList.style.display = 'none';
        }
    });
});
</script>
@endsection
