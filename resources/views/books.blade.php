@extends('layout')

@section('title', 'Kitaplar')

@section('css')
<style>
:root {
    --primary-color: #4361ee;
    --primary-light: #eef2ff;
    --primary-dark: #3a56d4;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-900: #111827;
    --box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    --transition: all 0.25s ease;
    --radius: 12px;
}

option {
    color: black;
}

.card {
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    transition: var(--transition);
    box-shadow: var(--box-shadow);
    background: #fff;
    margin-bottom: 16px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.08);
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 8px;
    line-height: 1.4;
}

.card-text {
    font-size: 0.95rem;
    color: var(--gray-700);
    margin-bottom: 12px;
    font-weight: 400;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid var(--gray-200);
}

.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid var(--gray-200);
}

.modal-title {
    font-size: 1.5rem;
    font-weight: bold;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin: 24px 0;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.95rem;
    color: var(--gray-700);
    background: white;
    border: 1px solid var(--gray-200);
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background: var(--primary-light);
    color: var(--primary-color);
    border-color: var(--primary-color);
    text-decoration: none;
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination .page-item.disabled .page-link {
    background: var(--gray-100);
    color: var(--gray-400);
    pointer-events: none;
}

.page-info {
    text-align: center;
    color: var(--gray-500);
    font-size: 0.9rem;
    margin-top: 12px;
    padding: 8px;
    background: white;
    border-radius: 8px;
    box-shadow: var(--box-shadow);
}

.filter-card {
    background-color: white;
    border-radius: var(--radius);
    padding: 24px;
    box-shadow: var(--box-shadow);
    border: 1px solid var(--gray-200);
}

.filter-card h5 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 16px;
}

.filter-card .form-label {
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 8px;
    display: block;
}

.filter-card .btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 20px;
    font-weight: 500;
    transition: var(--transition);
}

.filter-card .btn:hover {
    background-color: var(--primary-dark);
}

.book-card {
    border-radius: 8px;
    overflow: hidden;
    height: 250px;
    position: relative;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
    width: 100%;
}

.book-card:hover {
    position: absolute;
    height: auto;
    min-height: 250px;
    max-height: 80vh;
    transform: translateY(-10px);
    box-shadow: 0 20px 25px rgba(0,0,0,0.1);
    z-index: 100;
    left: 0;
    right: 0;
}

.book-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    background-image: inherit;
    filter: blur(10px);
    transform: scale(1.1);
    z-index: 0;
}

.book-card .card-body {
    position: relative;
    z-index: 2;
    background: rgba(255, 255, 255, 0.3);
    height: 100%;
    padding: 20px;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    overflow: hidden;
}

/* Scroll barı kaldır */
.book-card .card-body::-webkit-scrollbar {
    display: none;
}

.book-card .card-body {
    -ms-overflow-style: none;  /* IE ve Edge için */
    scrollbar-width: none;  /* Firefox için */
}

.book-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #000;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
    margin-bottom: 5px;
}

.book-info {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 8px;
    padding: 15px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    visibility: hidden;
}

.book-card:hover .book-info {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
}

.book-header {
    position: relative;
    z-index: 2;
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.book-card:not(:hover) .book-header {
    background: rgba(255, 255, 255, 0.95);
}

.book-author {
    color: #1a1a1a;
    font-size: 0.9rem;
    font-weight: 500;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.6);
    margin: 0;
}

.info-row {
    display: flex;
    justify-content: space-between;
    font-size: small;
    padding: 4px 0;
    color: #4a5568;
    margin-bottom: 8px;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transition-delay: calc(var(--index) * 0.05s);
}

.info-row:last-child {
    margin-bottom: 0;
}

.book-card:hover .info-row {
    opacity: 1;
    transform: translateY(0);
}

.info-label {
    color: #000;
    font-weight: 600;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
}

.info-value {
    color: #1a1a1a;
    font-weight: 500;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.6);
}

.status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 3;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.status-available {
    background: rgba(16, 185, 129, 0.95);
    color: white;
}

.status-borrowed {
    background: rgba(239, 68, 68, 0.95);
    color: white;
}

.status-reserved {
    background: rgba(245, 158, 11, 0.95);
    color: white;
}

.book-card img {
    width: 100%;
    height: 180px;
    object-fit: contain;
    background: #fafafa;
    padding: 8px;
    border-bottom: 1px solid #f1f5f9;
}

.book-details {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
    font-size: 0.8rem;
    color: #64748b;
    line-height: 1.4;
}

.book-card .btn {
    padding: 6px 12px;
    font-size: 0.8rem;
    border-radius: 4px;
    margin-top: 8px;
}

.footer {
    background-color: #343a40;
    color: white;
    padding: 16px;
    text-align: center;
    border-radius: var(--radius);
    margin-top: 24px;
}

.book-link {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.book-link:hover {
    color: inherit;
}

.book-details {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid var(--gray-200);
    font-size: 0.95rem;
    color: var(--gray-700);
    line-height: 1.6;
    word-break: break-word;
}

.book-details strong {
    color: var(--gray-900);
    font-weight: 600;
}

.search-section {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-bottom: 24px;
    background-color: white;
    padding: 20px;
    border-radius: var(--radius);
    box-shadow: var(--box-shadow);
    border: 1px solid var(--gray-200);
}

.search-form {
    display: flex;
    gap: 12px;
    width: 100%;
}

.search-form .form-control {
    flex: 1;
    min-width: 200px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    padding: 10px 16px;
    transition: var(--transition);
}

.search-form .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
}

.search-form .form-select {
    width: auto;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    padding: 10px 16px;
    transition: var(--transition);
}

.search-form .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
}

.search-form .btn {
    padding: 10px 20px;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
}

.search-form .btn:hover {
    background-color: var(--primary-dark);
}

.search-form .btn-secondary {
    background-color: var(--gray-300);
    color: var(--gray-700);
}

.search-form .btn-secondary:hover {
    background-color: var(--gray-500);
    color: white;
}

.col-md-6, .col-lg-4 {
    position: relative;
}

.custom-pagination {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.pagination-info {
    color: var(--gray-600);
    font-size: 0.9rem;
    background: white;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    box-shadow: var(--box-shadow);
}

.pagination-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.page-btn {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    color: var(--gray-700);
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.page-btn:hover {
    background: var(--primary-light);
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.page-btn.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.page-dots {
    color: var(--gray-500);
    padding: 0 0.5rem;
}

@media (max-width: 768px) {
    .search-form {
        flex-wrap: wrap;
    }
    
    .search-form .form-control {
        width: 100%;
        min-width: 100%;
    }
    
    .search-form .form-select {
        width: 100%;
    }
    
    .search-form .btn {
        width: 48%;
    }
    
    .book-card .card-body {
        padding: 16px;
    }
    
    .book-details {
        font-size: 0.9rem;
    }
}
</style>
@endsection

@section('content')
<div class="search-section">
    <form id="live-search-form" class="search-form" autocomplete="off">
        <input type="text" name="search" id="live-search-input" class="form-control" placeholder="Arama..." value="">
        <select name="search_type" id="live-search-type" class="form-select">
            <option value="all" selected>Tümü</option>
            <option value="book_name">Kitap Adı</option>
            <option value="author">Yazar</option>
            <option value="isbn">ISBN</option>
            <option value="category">Kategori</option>
            <option value="publisher">Yayın Evi</option>
        </select>
        <button type="submit" class="btn btn-primary">Ara</button>
        <button type="button" id="clear-search" class="btn btn-secondary" style="display:none;">Temizle</button>
    </form>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="filter-card">
            <h5 class="mb-3">Filtreler</h5>
            <form id="live-filter-form">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select" id="filter-category">
                        <option value="">Tümü</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Yayınevi</label>
                    <select name="publisher" class="form-select" id="filter-publisher">
                        <option value="">Tümü</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Yazar</label>
                    <select name="author" class="form-select" id="filter-author">
                        <option value="">Tümü</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select" id="filter-status">
                        <option value="">Tümü</option>
                        <option value="available">Müsait</option>
                        <option value="borrowed">Ödünç Alınmış</option>
                        <option value="reserved">Rezerve Edilmiş</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Yayın Yılı</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" class="form-control" name="min_year" id="filter-min-year" placeholder="Min" min="1800" max="{{ date('Y') }}">
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control" name="max_year" id="filter-max-year" placeholder="Max" min="1800" max="{{ date('Y') }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sayfa Sayısı</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" class="form-control" name="min_pages" id="filter-min-pages" placeholder="Min" min="0">
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control" name="max_pages" id="filter-max-pages" placeholder="Max" min="0">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-9">
        <div id="books-list">
            @include('partials.books-list', ['copies' => $copies])
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('live-search-input');
    const searchType = document.getElementById('live-search-type');
    const searchForm = document.getElementById('live-search-form');
    const filterForm = document.getElementById('live-filter-form');
    const booksList = document.getElementById('books-list');
    const clearBtn = document.getElementById('clear-search');
    let timer = null;

    function fetchBooks() {
        const params = new URLSearchParams(new FormData(filterForm));
        params.set('search', searchInput.value);
        params.set('search_type', searchType.value);

        fetch('/books/filter?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            booksList.innerHTML = html;
        });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(fetchBooks, 300);
        clearBtn.style.display = this.value ? 'inline-block' : 'none';
    });

    searchType.addEventListener('change', fetchBooks);

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        fetchBooks();
        this.style.display = 'none';
    });

    filterForm.addEventListener('change', fetchBooks);

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchBooks();
    });

    window.changePage = function(page) {
        const params = new URLSearchParams();
        
        // Form verilerini ekleasd
        const formData = new FormData(filterForm);
        for(let pair of formData.entries()) {
            params.append(pair[0], pair[1]);
        }
        
        // Arama verilerini ekle
        params.set('search', searchInput.value);
        params.set('search_type', searchType.value);
        params.set('page', page);

        fetch('/books/filter?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            booksList.innerHTML = html;
            window.scrollTo({top: booksList.offsetTop - 100, behavior: 'smooth'});
        })
        .catch(error => console.error('Pagination error:', error));
    };
});
</script>
@endsection