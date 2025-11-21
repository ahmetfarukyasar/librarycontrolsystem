<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'tel',
        'is_admin',
        'tcno',
        'avatar',
    ];

    protected $hidden =[
        'password',
    ];

    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function borrowings()
    {
        return $this->hasMany(BorrowedBook::class);
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function ratings()
    {
        return $this->hasMany(BookRating::class);
    }
}
