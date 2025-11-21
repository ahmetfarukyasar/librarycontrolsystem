@extends('layout')

@section('title', 'Yeni Kitap Tanıt')

@section('content')
<div class="container">
    <h2 class="mb-4">Yeni Kitap Tanıt</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="book_name" class="form-label">Kitap Adı</label>
                <input type="text" class="form-control" id="book_name" name="book_name" value="{{ old('book_name') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="author" class="form-label">Yazar</label>
                <select class="form-select" id="author_id" name="author_id" required>
                    <option value="">Yazar Seçin</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-8 mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="page_count" class="form-label">Sayfa Sayısı</label>
                <input type="number" class="form-control" id="page_count" name="page_count" value="{{ old('page_count') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="publisher" class="form-label">Yayınevi</label>
                <select class="form-select" id="publisher_id" name="publisher_id" required>
                    <option value="">Yayınevi Seçin</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="publish_year" class="form-label">Yayın Yılı</label>
                <input type="number" class="form-control" id="publish_year" name="publish_year" value="{{ old('publish_year') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Kategori Seçin</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="language_id" class="form-label">Dil</label>
                <select class="form-select" id="language_id" name="language_id" required>
                    <option value="">Dil Seçin</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" {{ old('language_id') == $language->id ? 'selected' : '' }}>
                            {{ $language->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label for="book_cover" class="form-label">Kitap Kapağı</label>
                <input type="file" class="form-control" id="book_cover" name="book_cover" accept="image/*">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Kitap Ekle</button>
                <a href="{{ route('admin.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </div>
    </form>
</div>
@endsection