<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'book_copy_id',
        'status',
        'request_date',
        'approval_date'
    ];

    protected $dates = ['request_date', 'approval_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }

    public function getDaysLeftAttribute()
    {
        if ($this->status !== 'approved' || !$this->approval_date) {
            return null;
        }
        $expireDate = \Carbon\Carbon::parse($this->approval_date)->addDays(3);
        $now = \Carbon\Carbon::now();
        return $now->diffInDays($expireDate, false);
    }
}
