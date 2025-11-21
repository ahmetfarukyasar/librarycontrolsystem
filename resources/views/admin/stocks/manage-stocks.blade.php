@extends('layout')

@section('title', 'Stokları Yönet')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Stokları Yönet</h2>
            <form class="d-flex align-items-center" method="GET">
                <label for="sort" class="me-2">Sıralama:</label>
                <select name="sort" id="sort" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>Kitap Adına göre</option>
                    <option value="isbn" {{ request('sort') == 'isbn' ? 'selected' : '' }}>ISBN'e göre</option>
                </select>
            </form>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kitap Adı</th>
                        @if(request('sort') == 'isbn')
                        <th>ISBN</th>
                        @endif
                        <th>Kopya Sayısı</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>{{ $book->book_name }}</td>
                            @if(request('sort') == 'isbn')
                            <td>{{ $book->isbn }}</td>
                            @endif
                            <td>{{ $book->copies_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
