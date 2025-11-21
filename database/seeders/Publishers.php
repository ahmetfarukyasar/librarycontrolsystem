<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Publishers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Publisher::insert([
            ['name' => 'Yapı Kredi Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Can Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'İletişim Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Türkiye İş Bankası Kültür Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Doğan Kitap', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Remzi Kitabevi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alfa Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Metis Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'İthaki Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Altın Kitaplar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pegasus Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Everest Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tudem Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bilgi Yayınevi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kırmızı Kedi Yayınevi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sel Yayıncılık', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pan Yayıncılık', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Destek Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Timaş Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kapı Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Say Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hep Kitap', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Martı Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Domingo Yayınevi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Epsilon Yayınevi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Karakutu Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ayrıntı Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cem Yayınevi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dergah Yayınları', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tekin Yayınevi', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
