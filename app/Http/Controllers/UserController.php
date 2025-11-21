<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use App\Models\BorrowedBook;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function userProfiles(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('tcno', $request->search);
        }

        $users = $query->with(['borrowings' => function($query) {
            $query->orderBy('purchase_date', 'desc');
        }, 'borrowings.copy.book'])->get();
        
        return view('admin.user-profiles', compact('users'));
    }

    public function userDetail($id)
    {
        $user = User::with(['borrowings.copy.book'])->findOrFail($id);
        $bookCopies = BookCopy::all();
        Reservation::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereNotNull('approval_date')
            ->where('approval_date', '<', Carbon::now()->subDays(3))
            ->update(['status' => 'expired']);

        $activeReservations = Reservation::with(['bookCopy.book'])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereNotNull('approval_date')
            ->where('approval_date', '>=', Carbon::now()->subDays(3))
            ->get();

        return view('admin.user-detail', compact('user', 'activeReservations', 'bookCopies'));
    }

    public function userBorrowBook(Request $request, $userId)
    {
        $request->validate([
            'barcode' => 'required',
            'return_date' => 'required|date|after:today'
        ]);

        $copy = BookCopy::where('barcode', $request->barcode)->first();

        if (!$copy) {
            return back()->with('borrow_error', 'Bu barkod numarasına sahip kitap bulunamadı.')->withInput();
        }

        if ($copy->status !== 'available') {
            return back()->with('borrow_error', 'Bu kitap ödünç vermeye uygun değil.')->withInput();
        }

        $borrowedBook = BorrowedBook::create([
            'user_id' => $userId,
            'copy_id' => $copy->id,
            'purchase_date' => now(),
            'return_date' => $request->return_date,
            'status' => 'borrowed'
        ]);

        $copy->status = 'borrowed';
        $copy->save();

        return redirect()->back()->with('success', 'Kitap başarıyla ödünç verildi.');
    }

    public function returnBook($id)
    {
        $borrow = BorrowedBook::findOrFail($id);
        $borrow->status = 'returned';
        $borrow->returned_at = now();
        $borrow->save();

        $borrow->copy->status = 'available';
        $borrow->copy->save();

        return redirect()->back()->with('success', 'Kitap başarıyla teslim alındı.');
    }

    public function extendDueDate(Request $request, $id)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:30'
        ]);

        $borrow = BorrowedBook::findOrFail($id);
        $borrow->return_date = $borrow->return_date->addDays(intval($request->days));
        $borrow->save();

        return redirect()->back()->with('success', 'Süre başarıyla uzatıldı.');
    }

    public function extendBorrowDate(BorrowedBook $borrow)
    {
        // Check if user owns this borrow or is admin
        if (Auth::id() != $borrow->user_id) {
            return back()->with('error', 'Bu işlem için yetkiniz yok.');
        }

        // Check if book is still borrowed
        if ($borrow->status != 'borrowed') {
            return back()->with('error', 'Bu kitap için süre uzatılamaz.');
        }

        // Check extension limit
        if ($borrow->extension_count >= 3) {
            return back()->with('error', 'Bu kitap için maksimum uzatma sayısına ulaşıldı.');
        }

        // Extend return date by 7 days and increment extension count
        $borrow->return_date = Carbon::parse($borrow->return_date)->addDays(7);
        $borrow->extension_count = ($borrow->extension_count ?? 0) + 1;
        $borrow->save();

        return back()->with('success', 'İade süresi başarıyla uzatıldı.');
    }
}
