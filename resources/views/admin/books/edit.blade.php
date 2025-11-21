@extends('layout')

@section('title', 'Kitap Düzenle')

@section('content')
<div class="container">
    <h2 class="mb-4">Kitap Düzenle</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.updateBook', $book->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="book_name" class="form-label">Kitap Adı</label>
                <input type="text" class="form-control" id="book_name" name="book_name" value="{{ $book->book_name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="author_id" class="form-label">Yazar</label>
                <select class="form-select" id="author_id" name="author_id" required>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="{{ $book->isbn }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="publisher_id" class="form-label">Yayınevi</label>
                <select class="form-select" id="publisher_id" name="publisher_id" required>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ $book->publisher_id == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="language_id" class="form-label">Dil</label>
                <select class="form-select" id="language_id" name="language_id" required>
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" {{ $book->language_id == $language->id ? 'selected' : '' }}>
                            {{ $language->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="page_count" class="form-label">Sayfa Sayısı</label>
                <input type="number" class="form-control" id="page_count" name="page_count" value="{{ $book->page_count }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="publish_year" class="form-label">Yayın Yılı</label>
                <input type="number" class="form-control" id="publish_year" name="publish_year" value="{{ $book->publish_year }}" required>
            </div>

            <div class="col-12 mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $book->description }}</textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.listBooks') }}" class="btn btn-secondary">İptal</a>
            </div>
        </div>
    </form>
</div>
@endsection
