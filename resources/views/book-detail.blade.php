@extends('layout')

@section('title', $book->book_name)

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
.book-detail {
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.book-cover {
    width: 100%;
    max-height: 500px;
    object-fit: contain;
    margin-bottom: 20px;
}

.book-info h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.book-meta {
    color: #666;
    margin-bottom: 30px;
}

.book-description {
    line-height: 1.8;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.interaction-section {
    border-top: 1px solid #eee;
    padding-top: 20px;
}

.like-buttons {
    display: none;
}

.like-btn, .dislike-btn {
    display: none;
}

.comments-section {
    margin-top: 30px;
}

.comment-form {
    margin-bottom: 20px;
}

.comment-list {
    max-height: 500px;
    overflow-y: auto;
}

.comment {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.rating-section {
    margin: 20px 0;
}

.star-rating {
    font-size: 24px;
    cursor: pointer;
    direction: ltr;
}

.star-rating .star {
    display: inline-block;
    position: relative;
    width: 1.2em;
}

.star-rating .star:before {
    content: '\f005';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: #e4e5e9;
    transition: color 0.2s ease;
}

.star-rating .star.filled:before {
    color: #ffc107;
}

.star-rating .star.half:before {
    background: linear-gradient(90deg, #ffc107 50%, #e4e5e9 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.star-rating .star:hover:before,
.star-rating .star:hover ~ .star:before {
    color: #ffc107;
}

.average-rating {
    font-size: 18px;
    margin: 10px 0;
}
</style>
@endsection

@section('content')
<div class="book-detail">
    <div class="row">
        <div class="col-md-4">
            <img src="{{ $book->book_cover }}" alt="{{ $book->book_name }}" class="book-cover">
        </div>
        <div class="col-md-8 book-info">
            <h1>{{ $book->book_name }}</h1>
            <div class="book-meta">
                <p><strong>Yazar:</strong> {{ $book->author->name }}</p>
                <p><strong>Yayınevi:</strong> {{ $book->publisher->name }}</p>
                <p><strong>Yayın Yılı:</strong> {{ $book->publish_year }}</p>
                <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                <p><strong>Sayfa Sayısı:</strong> {{ $book->page_count }}</p>
                <p><strong>Kategori:</strong> {{ $book->category->category_name }}</p>
                <p><strong>Türler:</strong> 
                    @foreach($book->genres as $genre)
                        {{ $genre->genre_name }}@if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
            
            <div class="book-description">
                <h3>Kitap Hakkında</h3>
                <p>{{ $book->description ?? 'Kitap açıklaması henüz eklenmemiş.' }}</p>
            </div>

            <div class="interaction-section">
                <div class="rating-section">
                    <h3>Kitabı Puanla</h3>
                    <div class="average-rating mb-3">
                        <strong>Ortalama Puan:</strong> {{ number_format($book->average_rating, 1) }}/5.0 
                        ({{ $book->ratings_count }} oylama)
                    </div>
                    <!-- -->
                    @auth
                    <form action="/books/{{ $book->id }}/rate" method="POST" id="ratingForm">
                        @csrf
                        <input type="hidden" name="rating" id="ratingInput">
                        <div class="star-rating" id="starRating">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $book->user_rating && $book->user_rating->rating >= $i ? 'filled' : 
                                    ($book->user_rating && $book->user_rating->rating > $i - 0.5 ? 'half' : '') }}" 
                                    data-rating="{{ $i }}"
                                    data-index="{{ $i }}"></span>
                            @endfor
                        </div>
                    </form>
                    @else
                        <p class="text-muted">Puan vermek için <a href="/auth">giriş yapın</a></p>
                    @endauth
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="card-text">{{ $book->description }}</p>
                        </div>
                        <div class="col-md-4">
                            @auth
                                @if($bookCopy && $bookCopy->status == 'available')
                                    <button type="button" class="btn btn-primary btn-lg w-100 mb-3 reserve-book" 
                                            data-id="{{ $bookCopy->id }}" 
                                            data-barcode="{{ $bookCopy->barcode }}">
                                        <i class="bi bi-bookmark-plus"></i> 
                                        Rezerve Et
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                                        Bu kitap şu anda müsait değil
                                    </button>
                                @endif
                            @else
                                <a href="/auth" class="btn btn-secondary btn-lg w-100 mb-3">
                                    Rezerve etmek için giriş yapın
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="comments-section">
                    <h3>Yorumlar</h3>
                    <form class="comment-form" action="/books/{{ $book->id }}/comment" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Yorumunuzu yazın..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Yorum Yap</button>
                    </form>

                    <div class="comment-list">
                        @foreach($book->comments as $comment)
                        <div class="comment">
                            <strong>{{ $comment->user->name }}</strong>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            <p>{{ $comment->comment }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('ratingInput');
    const form = document.getElementById('ratingForm');
    let currentRating = {{ $book->user_rating ? $book->user_rating->rating : 0 }};

    stars.forEach(star => {
        star.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const width = rect.right - rect.left;
            const mouseX = e.clientX - rect.left;
            const index = parseInt(this.dataset.index);
            let rating;

            if (mouseX < width * 0.5) {
                rating = index - 0.5;
            } else {
                rating = index;
            }

            highlightStars(rating);
        });

        star.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const width = rect.right - rect.left;
            const mouseX = e.clientX - rect.left;
            const index = parseInt(this.dataset.index);
            
            currentRating = mouseX < width * 0.5 ? index - 0.5 : index;
            ratingInput.value = currentRating;
            form.submit();
        });
    });

    const starRating = document.querySelector('.star-rating');
    starRating.addEventListener('mouseleave', function() {
        highlightStars(currentRating);
    });

    function highlightStars(rating) {
        stars.forEach(star => {
            const index = parseInt(star.dataset.index);
            star.classList.remove('filled', 'half');
            
            if (index <= rating) {
                star.classList.add('filled');
            } else if (index - 0.5 === rating) {
                star.classList.add('half');
            }
        });
    }

    // Sayfa yüklendiğinde mevcut puanı göster
    if (currentRating > 0) {
        highlightStars(currentRating);
    }

    const reserveButtons = document.querySelectorAll('.reserve-book');
    reserveButtons.forEach(reserveButton => {
        reserveButton.addEventListener('click', function() {
            const copyId = this.dataset.id;
            const barcode = this.dataset.barcode;
            if(confirm(`Kopya #${barcode} için rezervasyon yapmak istediğinizden emin misiniz?`)) {
                fetch(`/books/${copyId}/reserve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Rezervasyon isteğiniz alındı.');
                        reserveButton.disabled = true;
                        reserveButton.textContent = 'Rezervasyon İsteği Gönderildi';
                        reserveButton.classList.replace('btn-primary', 'btn-secondary');
                    } else {
                        alert(data.message || 'Bir hata oluştu.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Bir hata oluştu.');
                });
            }
        });
    });
});
</script>
@endsection
