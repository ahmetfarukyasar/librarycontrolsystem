<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcquisitionSource extends Model
{
    protected $fillable = ['name', 'description'];

    public function acquisitions()
    {
        return $this->hasMany(Acquisition::class);
    }
}
