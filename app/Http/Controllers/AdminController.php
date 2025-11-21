<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Language;
use App\Models\Publisher;
use App\Models\BookCopy;
use App\Models\User;
use App\Models\BorrowedBook;
use App\Models\ShelfLocation;
use App\Models\BorrowRequest;
use App\Models\AcquisitionSource;
use App\Models\Acquisition;
use App\Models\Reservation;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->is_admin == 0) {
            return back()->with('error', 'Bu sayfaya erişim izniniz yok.');
        }
        $totalBooks = Book::count();
        $totalCopies = BookCopy::count();
        $totalUsers = User::count();
        $totalActivities = Activity::count();
        $totalBorrowedBooks = BookCopy::where('status', 'borrowed')->count();
        $totalAvailableBooks = BookCopy::where('status', 'available')->count();

        $activities = Activity::all();
        return view('admin', compact('activities', 'totalBooks', 'totalCopies', 'totalUsers', 'totalActivities', 'totalBorrowedBooks', 'totalAvailableBooks'));
    }

    public function createBook()
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        $authors = Author::all();
        $languages = Language::all();
        return view('admin.books.create', compact('categories', 'publishers', 'authors', 'languages')); 
    }

    public function manageStocks(Request $request)
    {
        $sort = $request->get('sort', 'name');
        
        if ($sort == 'name') {
            $books = Book::select('book_name')
                ->selectRaw('COUNT(book_copies.id) as copies_count')
                ->leftJoin('book_copies', 'books.id', '=', 'book_copies.book_id')
                ->groupBy('book_name')
                ->orderBy('book_name');
        } else {
            $books = Book::select('book_name', 'isbn')
                ->withCount('bookCopies as copies_count')
                ->orderBy('isbn');
        }

        $books = $books->get();
        return view('admin.stocks.manage-stocks', compact('books', 'sort'));
    }// asa

    public function listBooks()
    {
        $books = Book::with('category', 'genres', 'bookCopy')->get();

        return view('admin.books.list', compact('books'));
    }

    public function manageCopies(Request $request) {
        $search = $request->search;
        $searchType = $request->search_type ?? 'all';

        $copies = BookCopy::with(['book', 'acquisition.source'])
            ->when($search, function($query) use ($search, $searchType) {
                $query->where(function($q) use ($search, $searchType) {
                    if ($searchType === 'all') {
                        $q->whereHas('book', function($b) use ($search) {
                            $b->where('book_name', 'like', "%{$search}%")
                              ->orWhere('isbn', 'like', "%{$search}%");
                        })
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhereHas('acquisition.source', function($a) use ($search) {
                            $a->where('name', 'like', "%{$search}%");
                        })
                        ->orWhere('shelf_location', 'like', "%{$search}%");
                    } elseif ($searchType === 'book_name') {
                        $q->whereHas('book', function($b) use ($search) {
                            $b->where('book_name', 'like', "%{$search}%");
                        });
                    } elseif ($searchType === 'isbn') {
                        $q->whereHas('book', function($b) use ($search) {
                            $b->where('isbn', 'like', "%{$search}%");
                        });
                    } elseif ($searchType === 'barcode') {
                        $q->where('barcode', 'like', "%{$search}%");
                    } elseif ($searchType === 'acquisition_source') {
                        $q->whereHas('acquisition.source', function($a) use ($search) {
                            $a->where('name', 'like', "%{$search}%");
                        });
                    } elseif ($searchType === 'shelf_location') {
                        $q->where('shelf_location', 'like', "%{$search}%");
                    }
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.books.copies', compact('copies'));
    }

    public function manageBorrowings(Request $request) {
        $search = $request->search;
        
        $copies = BookCopy::with('book')
            ->when($search, function($query) use ($search) {
                $query->whereHas('book', function($q) use ($search) {
                    $q->where('isbn', 'like', '%'.$search.'%');
                })->orWhere('barcode', 'like', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();
            
        return view('admin.stocks.manage-borrowings', compact('copies'));
    }

    public function createCopy()
    {
        $books = Book::select('book_name')->distinct()->get();
        $acquisitionSources = AcquisitionSource::all();
        return view('admin.books.create-copy', compact('books', 'acquisitionSources'));
    }

    public function storeCopy(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'block' => 'required|in:A,B',
            'floor' => 'required|in:0,1,2',
            'row' => 'required|integer|min:1|max:21',
            'shelf' => 'required|integer|min:1|max:20',
            'position' => 'required|integer|min:1|max:150',
            'condition' => 'required|in:yıpranmamış,az yıpranmış,yıpranmış,çok yıpranmış',  
            'acquisition_source_id' => 'required|exists:acquisition_sources,id',
            'acquisition_date' => 'required|date',
            'acquisition_cost' => 'nullable|numeric',
            'acquisition_place' => 'nullable|string',
            'acquisition_invoice' => 'nullable|string'

        ]);

        try {
            DB::beginTransaction();

            $formattedLocation = sprintf(
                "%s-%d-%d-%d-%d",
                $request->block,
                $request->floor,
                $request->row,
                $request->shelf,
                $request->position
            );

            $bookCopy = BookCopy::create([
                'book_id' => $request->book_id,
                'shelf_location' => $formattedLocation,
                'status' => 'available',
                'condition' => $request->condition
            ]);

            Acquisition::create([
                'book_copy_id' => $bookCopy->id,
                'acquisition_source_id' => $request->acquisition_source_id,
                'acquisition_date' => $request->acquisition_date,
                'acquisition_cost' => $request->acquisition_cost,
                'acquisition_place' => $request->acquisition_place,
                'acquisition_invoice' => $request->acquisition_invoice

            ]);

            ShelfLocation::create([
                'book_copy_id' => $bookCopy->id,
                'block' => $request->block,
                'floor' => $request->floor,
                'row' => $request->row,
                'shelf' => $request->shelf,
                'position' => $request->position
            ]);

            DB::commit();
            return redirect()->route('admin.manageCopies')
                ->with('success', 'Kitap kopyası başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function showCopy($id)
    {
        $copy = BookCopy::with(['book', 'acquisition.source'])->findOrFail($id);
        
        // Eğer raf konumu varsa parçalara ayır
        if ($copy->shelf_location) {
            $locationParts = explode('-', $copy->shelf_location);
            $copy->locationDetails = [
                'block' => $locationParts[0] ?? '',
                'floor' => $locationParts[1] ?? '',
                'row' => $locationParts[2] ?? '',
                'shelf' => $locationParts[3] ?? '',
                'position' => $locationParts[4] ?? ''
            ];
        }
        
        return view('admin.books.copy-detail', compact('copy'));
    }

    public function editCopy($id)
    {
        $copy = BookCopy::with(['book', 'acquisition.source'])->findOrFail($id);
        $acquisitionSources = AcquisitionSource::all();
        
        // Eğer raf konumu varsa parçalara ayır
        if ($copy->shelf_location) {
            $locationParts = explode('-', $copy->shelf_location);
            $copy->locationDetails = [
                'block' => $locationParts[0] ?? '',
                'floor' => $locationParts[1] ?? '',
                'row' => $locationParts[2] ?? '',
                'shelf' => $locationParts[3] ?? '',
                'position' => $locationParts[4] ?? ''
            ];
        }
        
        return view('admin.books.edit-copy', compact('copy', 'acquisitionSources'));
    }

    public function updateCopy(Request $request, $id)
    {
        $request->validate([
            'condition' => 'required|in:yıpranmamış,az yıpranmış,yıpranmış,çok yıpranmış',
            'status' => 'required|in:available,borrowed,reserved,lost',
            'block' => 'required|in:A,B',
            'floor' => 'required|in:0,1,2',
            'row' => 'required|integer|min:1|max:21',
            'shelf' => 'required|integer|min:1|max:20',
            'position' => 'required|integer|min:1|max:150',
            'acquisition_source_id' => 'required|exists:acquisition_sources,id',
            'acquisition_date' => 'required|date',
            'acquisition_cost' => 'nullable|numeric'
        ]);

        try {
            DB::beginTransaction();

            $copy = BookCopy::findOrFail($id);
            
            // Raf konumu güncelleme
            $formattedLocation = sprintf("%s-%d-%d-%d-%d",
                $request->block,
                $request->floor,
                $request->row,
                $request->shelf,
                $request->position
            );
            
            $copy->shelf_location = $formattedLocation;
            $copy->condition = $request->condition;
            $copy->status = $request->status;
            $copy->save();

            // Raf konumu detaylarını güncelle
            $copy->shelfLocation->update([
                'block' => $request->block,
                'floor' => $request->floor,
                'row' => $request->row,
                'shelf' => $request->shelf,
                'position' => $request->position
            ]);

            // Edinme bilgilerini güncelle
            $copy->acquisition->update([
                'acquisition_source_id' => $request->acquisition_source_id,
                'acquisition_date' => $request->acquisition_date,
                'acquisition_cost' => $request->acquisition_cost,
                'acquisition_place' => $request->acquisition_place,
                'acquisition_invoice' => $request->acquisition_invoice
            ]);

            DB::commit();
            return redirect()->route('admin.manageCopies')
                ->with('success', 'Kitap kopyası başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function deleteCopy($id)
    {
        try {
            DB::beginTransaction();
            
            $copy = BookCopy::findOrFail($id);
            
            // İlişkili kayıtları sil
            if ($copy->acquisition) {
                $copy->acquisition->delete();
            }
            if ($copy->shelfLocation) {
                $copy->shelfLocation->delete();
            }
            
            $copy->delete();
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function manageAuthors()
    {
        $authors = Author::all();
        return view('admin.books.authors', compact('authors'));
    }

    public function createAuthor(Request $request)
    {
        $author = new Author();
        $author->name = $request->name;
        $author->biography = $request->biography;
        $author->image = $request->image;
        $author->website = $request->website;
        $author->save();

        return redirect()->route('admin.manageAuthors')->with('success', 'Yazar başarıyla eklendi.');
    }

    public function managePublishers()
    {
        $publishers = Publisher::all();
        return view('admin.books.publishers', compact('publishers'));
    }

    public function createPublisher(Request $request)
    {
        $publisher = new Publisher();
        $publisher->name = $request->name;
        $publisher->address = $request->address;
        $publisher->phone = $request->phone;
        $publisher->email = $request->email;
        $publisher->website = $request->website;
        $publisher->logo = $request->logo;
        $publisher->description = $request->description;
        $publisher->save();

        return redirect()->route('admin.managePublishers')->with('success', 'Yayınevi başarıyla eklendi.');
    }

    public function manageCategories()
    {
        $categories = Category::all();;
        return view('admin.books.categories', compact('categories'));     
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories'
        ]);

        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();

        return redirect()->route('admin.manageCategories')->with('success', 'Kategori başarıyla eklendi.');
    }

    public function deleteCategory($id)
    {
        try {
            DB::beginTransaction();
            
            $category = Category::findOrFail($id);
            
            // Önce kategoriye bağlı kitapları kontrol et
            if($category->books()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu kategoriye ait kitaplar olduğu için silinemez.'
                ]);
            }

            // İlişkili genre kayıtlarını sil
            $category->genres()->delete();
            
            // Kategoriyi sil
            $category->delete();
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Kategori başarıyla silindi.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Kategori silinirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteAuthor($id)
    {
        try {
            $author = Author::findOrFail($id);
            if($author->books()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Bu yazara ait kitaplar var. Önce kitapları silmelisiniz.']);
            }
            $author->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Yazar silinemedi: ' . $e->getMessage()]);
        }
    }

    public function deletePublisher($id)
    {
        try {
            $publisher = Publisher::findOrFail($id);
            if($publisher->books()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Bu yayınevine ait kitaplar var. Önce kitapları silmelisiniz.']);
            }
            $publisher->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Yayınevi silinemedi: ' . $e->getMessage()]);
        }
    }

    public function addUser()
    {
        return view('admin.members.add');
    }

    public function listUsers()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.members.list', compact('users'));
    }

    public function userRoles()
    {
        return view('admin.members.roles');
    }

    public function getBookIsbns($bookName)
    {
        $books = Book::where('book_name', $bookName)
            ->with('publisher')
            ->get();

        if ($books->isEmpty()) {
            return response()->json(['error' => 'Kitap bulunamadı']);
        }

        $result = [];
        foreach ($books as $book) {
            $result[] = [
                'id' => $book->id,
                'isbn' => $book->isbn,
                'publisher' => $book->publisher->name ?? 'Bilinmiyor'
            ];
        }

        return response()->json(['books' => $result]);
    }


    public function manageBorrows()
    {
        $borrowedBooks = BorrowedBook::with(['user', 'bookCopy.book'])
            ->where('status', 'borrowed')
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('admin.stocks.manage-borrowings', compact('borrowedBooks'));
    }


    
    public function borrowBook(Request $request, $id)
    {
        $copy = BookCopy::findOrFail($id);
        if ($copy->status != 'available') {
            return redirect()->back()->with('error', 'Bu kitap şu anda ödünç verilemez.');
        }
        $copy->status = 'borrowed';
        $copy->save();

        BorrowedBook::create([
            'copy_id' => $id,
            'user_id' => $request->user_id,
            'purchase_date' => now(),
            'return_date' => now()->addDays(14),
            'status' => 'borrowed'
        ]);

        return redirect()->route('admin.manageBorrowings')->with('success', 'Kitap başarıyla ödünç verildi.');
    }

    public function returnBook($id)
    {
        $copy = BookCopy::findOrFail($id);
        $copy->status = 'available';
        $copy->save();

        $borrowedBook = BorrowedBook::where('copy_id', $id)
            ->whereNull('returned_at')
            ->latest()
            ->first();

        if ($borrowedBook) {
            $returnedAt = now();
            $borrowedBook->returned_at = $returnedAt;
            $borrowedBook->status = 'returned';
            
            // Gecikme günü ve ceza hesaplama
            if ($returnedAt > $borrowedBook->return_date) {
                $delayDays = $returnedAt->diffInDays($borrowedBook->return_date);
                $borrowedBook->delay_day = $delayDays;
                $borrowedBook->late_fee = $delayDays * 5; // Günlük 5₺ ceza
            }
            
            $borrowedBook->save();
        }

        return response()->json(['success' => true]);
    }

    public function getDueDate($id)
    {
        $borrowedBook = BorrowedBook::where('copy_id', $id)
            ->whereNull('returned_at')
            ->where('status', 'borrowed')
            ->latest()
            ->first();

        return response()->json([
            'due_date' => $borrowedBook && $borrowedBook->return_date
                ? $borrowedBook->return_date->format('d.m.Y H:i')
                : null,
            'message' => $borrowedBook && $borrowedBook->return_date
                ? null
                : 'Aktif ödünç kaydı veya teslim tarihi bulunamadı'
        ]);
    }

    public function extendDueDate(Request $request, $id)
    {
        $borrowedBook = BorrowedBook::findOrFail($id);

        // Maksimum uzatma hakkı kontrolü (3 kez)
        if ($borrowedBook->extension_count >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimum uzatma hakkı (3) kullanılmış.'
            ]);
        }

        try {
            DB::beginTransaction();

            $borrowedBook->return_date = $borrowedBook->return_date->addDays($request->days);
            $borrowedBook->extension_count = ($borrowedBook->extension_count ?? 0) + 1;
            $borrowedBook->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Süre başarıyla uzatıldı. Kalan uzatma hakkı: ' . (3 - $borrowedBook->extension_count)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Süre uzatma işlemi başarısız oldu: ' . $e->getMessage()
            ]);
        }
    }

    public function searchUsers(Request $request)
    {
        if ($request->has('phone')) {
            $phone = $request->get('phone');
            $user = User::where('tel', 'LIKE', '%' . $phone . '%')->first();
            
            if ($user) {
                return response()->json([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'tel' => $user->tel
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Telefon numarası gerekli'
        ]);
    }

    public function createUser()
    {
        return view('admin.members.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'tcno' => 'required|string|size:11|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'tel' => 'required|string|max:20',
            'is_admin' => 'boolean'
        ]);

        $user = User::create([
            'tcno' => $request->tcno,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tel' => $request->tel,
            'is_admin' => $request->has('is_admin')
        ]);

        return redirect()->route('admin.listUsers')->with('success', 'Üye başarıyla oluşturuldu.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.members.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'tel' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'tel', 'address']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        $user->is_admin = $request->has('is_admin');
        $user->save();

        return redirect()->route('admin.listUsers')->with('success', 'Üye başarıyla güncellendi.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function manageAcquisitionSources()
    {
        $sources = AcquisitionSource::all();
        $acquisitions = Acquisition::with(['bookCopy.book', 'source'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.books.acquisition-sources', compact('sources', 'acquisitions'));
    }

    public function storeAcquisitionSource(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        AcquisitionSource::create($request->all());

        return redirect()->route('admin.manageAcquisitionSources')
            ->with('success', 'Edinme türü başarıyla eklendi.');
    }

    public function manageReservations()
    {
        $pendingReservations = Reservation::with(['user', 'bookCopy.book'])
            ->where('status', 'pending')
            ->orderBy('request_date', 'desc')
            ->get();

        $approvedReservations = Reservation::with(['user', 'bookCopy.book'])
            ->where('status', 'approved')
            ->orderBy('approval_date', 'desc')
            ->get();

        return view('admin.stocks.manage-reservations', 
            compact('pendingReservations', 'approvedReservations'));
    }

    public function approveReservation($id)
    {
        try {
            DB::beginTransaction();
            
            $reservation = Reservation::findOrFail($id);
            $bookCopy = $reservation->bookCopy;
            
            if($bookCopy->status !== 'available') {
                throw new \Exception('Bu kitap şu anda rezerve edilemez.');
            }
            
            $reservation->status = 'approved';
            $reservation->approval_date = now();
            $reservation->save();
            
            $bookCopy->status = 'reserved';
            $bookCopy->save();

            // Bildirim: Rezervasyon onaylandı
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => 'Rezervasyonunuz onaylandı: ' . $bookCopy->book->book_name . ' (Barkod: ' . $bookCopy->barcode . ')',
                'notification_type' => 'success',
                'read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function rejectReservation($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->status = 'rejected';
            $reservation->save();

            // Bildirim: Rezervasyon reddedildi
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => 'Rezervasyonunuz reddedildi: ' . $reservation->bookCopy->book->book_name . ' (Barkod: ' . $reservation->bookCopy->barcode . ')',
                'notification_type' => 'error',
                'read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function cancelReservation($id)
    {
        try {
            DB::beginTransaction();
            
            $reservation = Reservation::findOrFail($id);
            $bookCopy = $reservation->bookCopy;
            
            // Rezervasyonu iptal et
            $reservation->status = 'cancelled';
            $reservation->save();
            
            // Kitap durumunu müsait yap
            $bookCopy->status = 'available';
            $bookCopy->save();
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false, 
                'message' => 'Rezervasyon iptal edilirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function checkBarcode(Request $request, $userId)
    {
        if($request->has('action') && $request->action === 'borrow') {
            return $this->userBorrowBook($request, $userId);
        }

        $bookCopy = BookCopy::where('barcode', $request->barcode)
            ->with(['book.author', 'book.publisher'])
            ->first();

        if (!$bookCopy) {
            return back()->with('book_error', 'Kitap bulunamadı');
        }

        if ($bookCopy->status != 'available') {
            return back()->with('book_details', [
                'name' => $bookCopy->book->book_name,
                'author' => $bookCopy->book->author->name,
                'isbn' => $bookCopy->book->isbn,
                'publisher' => $bookCopy->book->publisher->name,
                'pages' => $bookCopy->book->page_count,
            ])->withInput()->with('book_error', 'Bu kitap şu anda ödünç verilemez.');
        }

        return back()->with('book_details', [
            'name' => $bookCopy->book->book_name,
            'author' => $bookCopy->book->author->name,
            'isbn' => $bookCopy->book->isbn,
            'publisher' => $bookCopy->book->publisher->name,
            'pages' => $bookCopy->book->page_count
        ])->withInput();
    }

    public function userBorrowBook(Request $request, $userId)
    {
        try {
            DB::beginTransaction();
            
            $bookCopy = BookCopy::where('barcode', $request->barcode)
                ->with('book')
                ->first();

            if (!$bookCopy) {
                return back()->with('borrow_error', 'Kitap bulunamadı.');
            }

            if ($bookCopy->status != 'available') {
                return back()->with('borrow_error', 'Bu kitap şu anda ödünç verilemez.');
            }

            // Ödünç verme kaydı oluştur
            $borrowing = BorrowedBook::create([
                'user_id' => $userId,
                'copy_id' => $bookCopy->id,
                'purchase_date' => now(),
                'return_date' => $request->return_date,
                'status' => 'borrowed'
            ]);

            // Kitap durumunu güncelle
            $bookCopy->update([
                'status' => 'borrowed',
                'is_borrowed' => true
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Kitap başarıyla ödünç verildi.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('borrow_error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
}
