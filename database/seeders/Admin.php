<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Admin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'tcno' => '12345678910',
            'email' => 'admin@example.com',
            'tel' => '5555555555',
            'password' => bcrypt('123456'),
            'is_admin' => true,
            'avatar' => null,
            'favori_kitap' => null,
            'favori_kategori' => null,
        ]);
    }
}
