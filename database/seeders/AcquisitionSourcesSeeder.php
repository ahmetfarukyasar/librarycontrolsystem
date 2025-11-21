<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcquisitionSource;

class AcquisitionSourcesSeeder extends Seeder
{
    public function run(): void
    {
        AcquisitionSource::insert([
            [
                'name' => 'Satın Alım',
                'description' => 'Kütüphaneye satın alınarak eklenen kitaplar',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bağış',
                'description' => 'Bağış yoluyla kütüphaneye kazandırılan kitaplar',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
