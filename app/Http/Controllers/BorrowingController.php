<?php

namespace App\Http\Controllers;

use App\Models\BorrowedBook;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {

    }

    public function extend(BorrowedBook $borrowing)
    {
        if ($borrowing->extension_count >= 3) {
            return back()->with('error', 'Maksimum uzatma hakkınızı kullandınız.');
        }

        $borrowing->return_date = $borrowing->return_date->addDays(15);
        $borrowing->extension_count += 1;
        $borrowing->save();

        return back()->with('success', 'Kitap teslim süresi 15 gün uzatıldı. Kalan uzatma hakkı: ' . (3 - $borrowing->extension_count));
    }
}