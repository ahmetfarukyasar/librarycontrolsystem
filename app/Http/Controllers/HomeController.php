<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Activity;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // En çok puan alan 3 kitabı al
        $featuredBooks = Book::select('books.*')
            ->selectRaw('(SELECT AVG(CAST(rating AS FLOAT)) FROM book_ratings WHERE books.id = book_ratings.book_id) as avg_rating')
            ->whereExists(function($query) {
                $query->select('id')
                    ->from('book_ratings')
                    ->whereRaw('books.id = book_ratings.book_id');
            })
            ->orderByRaw('(SELECT AVG(CAST(rating AS FLOAT)) FROM book_ratings WHERE books.id = book_ratings.book_id) DESC')
            ->take(3)
            ->get();

        // Son 5 aktiviteyi al
        $recentActivities = Activity::with('user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('main', compact('featuredBooks', 'recentActivities'));
    }
}
