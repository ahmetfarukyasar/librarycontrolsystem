<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    protected $fillable = [
        'book_name',
        'author_id',
        'page_count',
        'category_id',
        'isbn',
        'publisher_id',
        'publish_year',
        'description',
        'book_cover',
        'language_id'
    ];

    public $timestamps = false;
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function bookCopy()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function bookCopies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres');
    }

    public function comments()
    {
        return $this->hasMany(BookReview::class);
    }

    public function ratings() 
    {
        return $this->hasMany(BookRating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function ratingsCount() 
    {
        return $this->ratings()->count();
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }
}
