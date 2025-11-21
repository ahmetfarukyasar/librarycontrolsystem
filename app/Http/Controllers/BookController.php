<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Book;
use App\Models\Category;
use App\Models\Genre;
use App\Models\BookReview;
use App\Models\BookRating;
use App\Models\BorrowRequest;
use App\Models\BookCopy;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Language;
use App\Models\Reservation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        $authors = Author::all();
        
        $copies = $this->filter($request)->original->copies;
        
        return view('books', compact('copies', 'categories', 'publishers', 'authors'));
    }

    public function create()
    {
        $categories = Category::all();
        $genres = Genre::all();
        return view('books.create', compact('categories', 'genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'language_id' => 'required|exists:languages,id',
            'page_count' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'required|string|max:13|unique:books,isbn',
            'publisher_id' => 'required|exists:publishers,id',
            'publish_year' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $existingBook = Book::where('isbn', $request->isbn)->first();

        if ($existingBook) {
            return redirect()->back()->with('error', 'Bu ISBN numarasına sahip bir kitap zaten mevcut.');
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('storage/books'), $imageName);
            $data['image'] = 'books/' . $imageName;
        }

        $book = Book::create($data);


        Activity::create([
            'user_id' => Auth::id(),
            'activity_type' => 'book_creation',
            'activity_description' => 'Created book: ' . $request->book_name,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.listBooks')->with('success', 'Kitap başarıyla oluşturuldu.');
    }

    public function books(Request $request)
    {
        $query = Book::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genre_id', $request->genre);
            });
        }

        $books = $query->paginate(9);
        $categories = Category::all();
        $genres = Genre::all();

        return view('books', compact('books', 'categories', 'genres'));
    }

    public function show($id)
    {
        $bookCopy = BookCopy::with([
            'book.category',
            'book.comments.user',
            'book.ratings',
            'book.publisher',
            'book.author',
            'book.genres'
        ])->findOrFail($id);

        $book = $bookCopy->book;
        $book->average_rating = $book->ratings()->avg('rating') ?? 0;
        $book->ratings_count = $book->ratings()->count();

        if (Auth::check()) {
            $book->user_rating = $book->ratings()
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('book-detail', compact('book', 'bookCopy'));
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|min:3|max:1000'
        ]);

        BookReview::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'comment' => $request->comment,
            'comment_date' => now()
        ]);

        return back()->with('success', 'Yorumunuz eklendi.');
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|between:0.5,5.0'
        ]);

        $book = Book::findOrFail($id);
        $existingRating = BookRating::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->first();
        if ($existingRating) {
            $existingRating->update(['rating' => $request->rating]);
        } else {
            $rating = BookRating::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'book_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                ['rating' => $request->rating]
            );
        }
        
        Activity::create([
            'user_id' => Auth::id(),
            'activity_type' => 'rating',
            'activity_description' => 'Rated book: ' . $book->book_name . ' with ' . $request->rating . ' stars',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        

        return back()->with('success', 'Puanınız kaydedildi.');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        $languages = Language::all();
        
        return view('admin.books.edit', compact('book', 'categories', 'authors', 'publishers', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'language_id' => 'required|exists:languages,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'required|string|max:13|unique:books,isbn,'.$id,
            'page_count' => 'required|integer',
            'publish_year' => 'required|integer',
            'description' => 'nullable|string'
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());

        return redirect()->route('admin.listBooks')->with('success', 'Kitap başarıyla güncellendi.');
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kitap başarıyla silindi.'
        ]);
    }

    public function destroy(Book $book)
    {
        foreach ($book->bookCopies as $copy) {
            $copy->stockHistories()->delete();
            $copy->delete();
        }

        if ($book->ratings) {
            $book->ratings()->delete();
        }
        if ($book->comments) {
            $book->comments()->delete();
        }
        if ($book->image) {
            $imagePath = public_path('storage/' . $book->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Kitap başarıyla silindi.');
    }

    public function toggleStatus($id)
    {
        $book = Book::findOrFail($id);
        $book->status = $book->status === 'available' ? 'borrowed' : 'available';
        $book->save();

        return response()->json([
            'success' => true,
            'newStatus' => $book->status,
            'message' => 'Kitap durumu başarıyla güncellendi.'
        ]);
    }

    public function ajaxDelete($id, Request $request)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json([
            'success' => true,
            'message' => 'Kitap başarıyla silindi.'
        ]);
    }

    public function reserve($id)
    {
        try {
            // Her zaman BookCopy'nin id'siyle çalış
            $bookCopy = BookCopy::findOrFail($id);

            if ($bookCopy->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu kitap kopyası şu anda rezerve edilemez.'
                ]);
            }

            $existingReservation = Reservation::where('user_id', Auth::id())
                ->where('book_copy_id', $id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu kitap için zaten bir rezervasyon isteğiniz bulunmaktadır.'
                ]);
            }

            Reservation::create([
                'user_id' => Auth::id(),
                'book_copy_id' => $id,
                'status' => 'pending',
                'request_date' => now()
            ]);

            Activity::create([
                'user_id' => Auth::id(),
                'activity_type' => 'reservation_request',
                'activity_description' => Auth::user()->name . ' reserved ' . $bookCopy->book->book_name . ' (Barkod: ' . $bookCopy->barcode . ')',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Bildirim oluştur
            Notification::create([
                'user_id' => Auth::id(),
                'message' => 'Rezervasyon isteğiniz alındı: ' . $bookCopy->book->book_name . ' (Barkod: ' . $bookCopy->barcode . ')',
                'notification_type' => 'info',
                'read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rezervasyon isteğiniz alındı.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function filter(Request $request)
    {
        $query = BookCopy::with(['book' => function($q) {
            $q->with(['category', 'genres', 'author', 'publisher']);
        }]);

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->get('search');
            $searchType = $request->get('search_type', 'all');
            $query->whereHas('book', function($q) use ($search, $searchType) {
                if ($searchType === 'all') {
                    $q->where('book_name', 'like', "%{$search}%")
                      ->orWhereHas('author', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhere('isbn', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('category_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('publisher', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                } elseif ($searchType === 'book_name') {
                    $q->where('book_name', 'like', "%{$search}%");
                } elseif ($searchType === 'author') {
                    $q->whereHas('author', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                } elseif ($searchType === 'isbn') {
                    $q->where('isbn', 'like', "%{$search}%");
                } elseif ($searchType === 'category') {
                    $q->whereHas('category', function($q) use ($search) {
                        $q->where('category_name', 'like', "%{$search}%");
                    });
                } elseif ($searchType === 'publisher') {
                    $q->whereHas('publisher', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
            });
        }

        // Kategori filtresi
        if ($request->filled('category')) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Yayınevi filtresi
        if ($request->filled('publisher')) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('publisher_id', $request->publisher);
            });
        }

        // Yazar filtresi
        if ($request->filled('author')) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('author_id', $request->author);
            });
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Yayın yılı filtresi
        if ($request->filled('min_year') || $request->filled('max_year')) {
            $query->whereHas('book', function($q) use ($request) {
                if ($request->filled('min_year') && $request->filled('max_year')) {
                    $q->whereBetween('publish_year', [$request->min_year, $request->max_year]);
                } elseif ($request->filled('min_year')) {
                    $q->where('publish_year', '>=', $request->min_year);
                } elseif ($request->filled('max_year')) {
                    $q->where('publish_year', '<=', $request->max_year);
                }
            });
        }

        // Sayfa sayısı filtresi
        if ($request->filled('min_pages') || $request->filled('max_pages')) {
            $query->whereHas('book', function($q) use ($request) {
                if ($request->filled('min_pages') && $request->filled('max_pages')) {
                    $q->whereBetween('page_count', [$request->min_pages, $request->max_pages]);
                } elseif ($request->filled('min_pages')) {
                    $q->where('page_count', '>=', $request->min_pages);
                } elseif ($request->filled('max_pages')) {
                    $q->where('page_count', '<=', $request->max_pages);
                }
            });
        }

        $copies = $query->paginate(9);
        return response()->view('partials.books-list', compact('copies'));
    }

    public function getByBarcode($barcode)
    {
        $bookCopy = BookCopy::where('barcode', $barcode)
            ->with('book:id,book_name,isbn', 'book.author:id,name')
            ->first();

        return response()->json([
            'success' => $bookCopy ? true : false,
            'book' => $bookCopy ? [
                'book_name' => $bookCopy->book->book_name,
                'isbn' => $bookCopy->book->isbn,
                'author' => $bookCopy->book->author->name
            ] : null
        ]);
    }
}

