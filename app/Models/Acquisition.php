<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisition extends Model
{
    protected $fillable = [
        'book_copy_id',
        'acquisition_date',
        'acquisition_source_id',
        'acquisition_cost',
        'acquisition_place',
        'acquisition_invoice',
    ];

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }

    public function source()
    {
        return $this->belongsTo(AcquisitionSource::class, 'acquisition_source_id');
    }
}
