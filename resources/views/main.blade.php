@extends('layout')

@section('title', 'Ana Sayfa - Kütüphane')

@section('content')

    <div class="hero-section text-center py-5 mb-4">
        <h1>Hoş Geldiniz</h1>
        <p class="lead">Kitapları keşfedin, arkadaşlarınızla paylaşın ve okuma deneyiminizi zenginleştirin.</p>
        <div class="mt-4">
            <a href="/books" class="btn btn-primary me-2">Kitaplara Göz At</a>
            @if (auth()->check())
                <a href="/profile" class="btn btn-outline-primary">Profilim</a>
            @else
            <a href="/auth" class="btn btn-outline-primary">Giriş Yap</a>
            @endif
        </div>
    </div>


    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-4">Öne Çıkan Kitaplar</h3>
        </div>
        @foreach($featuredBooks as $book)
        <div class="col-md-4 mb-3">
            <a href="/books" class="text-decoration-none">
                <div class="card h-100">
                    <img src="{{ $book->book_cover ?? 'https://via.placeholder.com/350x500' }}" class="card-img-top book-cover" alt="{{ $book->book_name }}">
                    <div class="card-body">
                        <h5 class="card-title text-dark">{{ $book->book_name }}</h5>
                        <p class="card-text text-secondary">{{ $book->author->name }}</p>
                        <div class="text-warning mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($book->avg_rating ?? 0))
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                            <span class="text-secondary">({{ number_format($book->avg_rating ?? 0, 1) }})</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>



@endsection

@section('css')
<style>
    .hero-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 40px 20px;
    }
    .card {
        transition: transform 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    .book-cover {
        height: 400px;
        object-fit: cover;
    }
</style>
@endsection