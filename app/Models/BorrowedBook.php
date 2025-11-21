<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BorrowedBook extends Model
{
    protected $fillable = [
        'user_id',
        'copy_id',
        'purchase_date',
        'return_date',
        'returned_at',
        'status',
        'extension_count',
        'delay_day',
        'late_fee',
        'notes'
    ];

    protected $dates = [
        'purchase_date',
        'return_date',
        'returned_at'
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'return_date' => 'datetime',
        'returned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function copy()
    {
        return $this->belongsTo(BookCopy::class, 'copy_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('returned_at')->where('status', 'borrowed');
    }

    public function isOverdue()
    {
        return !$this->returned_at && Carbon::now()->isAfter($this->return_date);
    }

    public function calculateLateFee()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $returnDate = Carbon::parse($this->return_date);
        $now = Carbon::now();
        return $now->diffInDays($returnDate) * 5;
    }

    public function getFormattedPurchaseDateAttribute()
    {
        return $this->purchase_date ? $this->purchase_date->format('d.m.Y H:i') : null;
    }

    public function getFormattedReturnDateAttribute()
    {
        return $this->return_date ? $this->return_date->format('d.m.Y H:i') : null;
    }

    public function getFormattedReturnedAtAttribute()
    {
        return $this->returned_at ? $this->returned_at->format('d.m.Y H:i') : null;
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->status === 'returned') {
            return 0;
        }
        return (int)now()->startOfDay()->diffInDays($this->return_date->startOfDay(), false);
    }

    public function getStatusTextAttribute()
    {
        switch($this->status) {
            case 'borrowed':
                return 'Ödünç Verildi';
            case 'returned':
                return 'İade Edildi';
            case 'overdue':
                return 'Gecikmiş';
            default:
                return 'Bilinmeyen';
        }
    }

    public function getStatusColorAttribute()
    {
        switch($this->status) {
            case 'borrowed':
                return 'success';
            case 'returned':
                return 'info';
            case 'overdue':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
