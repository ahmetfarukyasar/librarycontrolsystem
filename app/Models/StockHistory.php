<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'copy_id',
        'event_type',
        'old_shelf',
        'new_shelf',
        'event_date',
        'note',
    ];

    public $timestamps = false;

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'copy_id');
    }
}
