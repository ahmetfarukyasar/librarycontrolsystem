<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShelfLocation extends Model
{
    protected $fillable = [
        'book_copy_id',
        'block',
        'floor',
        'row',
        'shelf',
        'position'
    ];

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }
}
