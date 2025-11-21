<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use App\Models\Genre;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/discover', [PageController::class, 'discover']);
Route::get('/login', [PageController::class, 'login']);
Route::get('/register', [PageController::class, 'register']);

Route::get('/friends', [FriendController::class, 'index']);

Route::prefix('adminpanel')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create-book', [AdminController::class, 'createBook'])->name('admin.createBook');
    Route::get('/list-books', [AdminController::class, 'listBooks'])->name('admin.listBooks');
    Route::get('/manage-categories', [AdminController::class, 'manageCategories'])->name('admin.manageCategories');
    Route::get('/manage-authors', [AdminController::class, 'manageAuthors'])->name('admin.manageAuthors');
    Route::post('/create-author', [AdminController::class, 'createAuthor'])->name('admin.createAuthor');
    Route::get('/manage-publishers', [AdminController::class, 'managePublishers'])->name('admin.managePublishers');
    Route::post('/create-publisher', [AdminController::class, 'createPublisher'])->name('admin.createPublisher');
    Route::get('/manage-copies', [AdminController::class, 'manageCopies'])->name('admin.manageCopies');
    Route::get('/create-copy', [AdminController::class, 'createCopy'])->name('admin.createCopy');
    Route::post('/store-copy', [AdminController::class, 'storeCopy'])->name('admin.storeCopy');
    Route::get('/edit-copy/{id}', [AdminController::class, 'editCopy'])->name('admin.editCopy');
    Route::post('/update-copy/{id}', [AdminController::class, 'updateCopy'])->name('admin.updateCopy');
    Route::delete('/copies/{id}', [AdminController::class, 'deleteCopy'])->name('admin.deleteCopy');
    Route::get('/copies/{id}', [AdminController::class, 'showCopy'])->name('admin.showCopy');
    Route::get('/books/{bookId}/isbns', [AdminController::class, 'getBookIsbns']);
    Route::post('/copies/{id}/borrow', [AdminController::class, 'borrowBook'])->name('admin.borrowBook');
    Route::post('/copies/{id}/return', [AdminController::class, 'returnBook'])->name('admin.returnBook');
    Route::get('/copies/{id}/due-date', [AdminController::class, 'getDueDate'])->name('admin.getDueDate');
    Route::post('/copies/{id}/extend', [AdminController::class, 'extendDueDate'])->name('admin.extendDueDate');
    Route::get('/users/search', [AdminController::class, 'searchUsers']);
    Route::get('/search-users', [AdminController::class, 'searchUsers'])->name('admin.searchUsers');
    Route::post('/create-category', [CategoryController::class, 'create'])->name('admin.createCategory');
    Route::get('/manage-stocks', [AdminController::class, 'manageStocks'])->name('admin.manageStocks');
    Route::get('/manage-borrowings', [AdminController::class, 'manageBorrowings'])->name('admin.manageBorrowings');
    Route::get('/create-user', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/store-user', [AdminController::class, 'storeUser'])->name('admin.storeUser');
    Route::get('/list-users', [AdminController::class, 'listUsers'])->name('admin.listUsers');
    Route::get('/user-roles', [AdminController::class, 'userRoles'])->name('admin.userRoles');
    Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::post('/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/adminpanel/user-profiles', [UserController::class, 'userProfiles'])->name('admin.userProfiles');
    Route::get('/user-detail/{id}', [UserController::class, 'userDetail'])->name('admin.userDetail');
    Route::post('/user/{id}/borrow-book', [UserController::class, 'userBorrowBook'])->name('admin.userBorrowBook');
    Route::post('/borrowed-books/{id}/return', [UserController::class, 'returnBook'])->name('admin.returnBook');
    Route::post('/borrowed-books/{id}/extend', [UserController::class, 'extendDueDate'])->name('admin.extendDueDate');
    Route::post('/return-book/{id}', [UserController::class, 'returnBook'])->name('admin.returnBook');
    Route::post('/extend-book/{id}', [UserController::class, 'extendDueDate'])->name('admin.extendDueDate');

    Route::get('/acquisition-sources', [AdminController::class, 'manageAcquisitionSources'])->name('admin.manageAcquisitionSources');
    Route::post('/acquisition-sources', [AdminController::class, 'storeAcquisitionSource'])->name('admin.storeAcquisitionSource');
    Route::get('/edit-book/{id}', [BookController::class, 'edit'])->name('admin.editBook');
    Route::post('/update-book/{id}', [BookController::class, 'update'])->name('admin.updateBook');
    Route::delete('/delete-book/{id}', [BookController::class, 'delete'])->name('admin.deleteBook');

    // Kategori rotaları
    Route::post('/categories/store', [AdminController::class, 'createCategory'])->name('admin.createCategory');
    Route::delete('/deleteCategory/{id}', [AdminController::class, 'deleteCategory'])->name('admin.deleteCategory');

    // Yazar rotaları
    Route::post('/authors', [AuthorController::class, 'store'])->name('admin.authors.store');
    Route::delete('/authors/{id}', [AuthorController::class, 'destroy'])->name('admin.authors.destroy');
    Route::put('/authors/{id}', [AuthorController::class, 'update'])->name('admin.authors.update');
    Route::post('/authors/store', [AdminController::class, 'createAuthor'])->name('admin.createAuthor');
    Route::delete('/authors/{id}', [AdminController::class, 'deleteAuthor'])->name('admin.deleteAuthor');

    // Yayınevi rotaları
    Route::post('/publishers', [PublisherController::class, 'store'])->name('admin.publishers.store');
    Route::delete('/publishers/{id}', [PublisherController::class, 'destroy'])->name('admin.publishers.destroy');
    Route::put('/publishers/{id}', [PublisherController::class, 'update'])->name('admin.publishers.update');
    Route::post('/publishers/store', [AdminController::class, 'createPublisher'])->name('admin.createPublisher');
    Route::delete('/publishers/{id}', [AdminController::class, 'deletePublisher'])->name('admin.deletePublisher');

    Route::get('/manage-reservations', [AdminController::class, 'manageReservations'])->name('admin.manageReservations');
    Route::post('/reservations/{id}/approve', [AdminController::class, 'approveReservation'])->name('admin.approveReservation');
    Route::post('/reservations/{id}/reject', [AdminController::class, 'rejectReservation'])->name('admin.rejectReservation');
    Route::post('/reservations/{id}/cancel', [AdminController::class, 'cancelReservation'])->name('admin.cancelReservation');

    Route::get('books/getByBarcode/{barcode}', [BookController::class, 'getByBarcode'])
        ->name('admin.books.getByBarcode');

    Route::post('/user/{id}/check-barcode', [AdminController::class, 'checkBarcode'])->name('admin.checkBarcode');
});

Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->middleware('auth');

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [RegisterController::class, 'login'])->name('login');
Route::get('/logout', [RegisterController::class, 'logout'])->name('logout');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/filter', [BookController::class, 'filter'])->name('books.filter');
Route::post('/books/create', [BookController::class, 'store'])->name('books.store');
Route::get('/books/{id}', [BookController::class, 'show']);
Route::post('/books/{id}/rate', [BookController::class, 'rate'])->middleware('auth');
Route::post('/books/{id}/comment', [BookController::class, 'comment'])->middleware('auth');
Route::post('/books/{copyId}/borrow', [BookController::class, 'borrowBook'])->name('books.borrow');
Route::post('/books/{copyId}/return', [BookController::class, 'returnBook'])->name('books.return');
Route::post('/books/{id}/reserve', [BookController::class, 'reserve'])->name('books.reserve')->middleware('auth');

Route::delete('/adminpanel/books/{id}/delete', [BookController::class, 'ajaxDelete'])->name('books.ajaxDelete');
Route::post('/adminpanel/books/{id}/toggle-status', [BookController::class, 'toggleStatus'])->name('books.toggleStatus');
Route::get('/adminpanel/addBook/', [BookController::class, 'addBook'])->name('books.addBook');

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth');
Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->middleware('auth');

Route::post('/friends/add', [FriendController::class, 'add'])->name('friends.add');

Route::get('/api/genres', function (Request $request) {
    $categoryId = $request->get('category_id');
    $genres = Genre::where('category_id', $categoryId)->get();
    return response()->json($genres);
});

Route::get('/api/books/search', function (Request $request) {
    $query = $request->get('query');
    $books = \App\Models\Book::with('author')
        ->where('book_name', 'like', "%{$query}%")
        ->limit(10)
        ->get()
        ->map(function($book) {
            return [
                'id' => $book->id,
                'book_name' => $book->book_name,
                'author' => $book->author ? $book->author->name : null,
                'isbn' => $book->isbn // <-- eklendi
            ];
        });
    return response()->json($books);
});

Route::get('/api/books/search-by-isbn', function (Request $request) {
    $isbn = $request->get('isbn');
    $book = \App\Models\Book::with('publisher')
        ->where('isbn', $isbn)
        ->first();
    if ($book) {
        return response()->json([
            'book' => [
                'id' => $book->id,
                'book_name' => $book->book_name,
                'isbn' => $book->isbn,
                'publisher' => $book->publisher ? $book->publisher->name : null
            ]
        ]);
    }
    return response()->json(['book' => null]);
});

Route::get('/api/books/search-by-id', function (Request $request) {
    $id = $request->get('id');
    $book = \App\Models\Book::with('publisher')
        ->where('id', $id)
        ->first();
    if ($book) {
        return response()->json([
            'book' => [
                'id' => $book->id,
                'book_name' => $book->book_name,
                'isbn' => $book->isbn,
                'publisher' => $book->publisher ? $book->publisher->name : null
            ]
        ]);
    }
    return response()->json(['book' => null]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/books/{id}/request-borrow', [BookController::class, 'requestBorrow'])->name('books.requestBorrow');
    Route::post('/admin/borrow-requests/{id}/update', [AdminController::class, 'updateBorrowRequest'])->name('admin.updateBorrowRequest');
    Route::get('/admin/borrow-requests', [AdminController::class, 'borrowRequests'])->name('admin.borrowRequests');
    Route::post('/user/extend-borrow/{borrow}', [UserController::class, 'extendBorrowDate'])->name('user.extendBorrowDate');
});




