<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Authors extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            'Yaşar Kemal', 'Orhan Pamuk', 'Nazım Hikmet', 'Sabahattin Ali', 'Ahmet Hamdi Tanpınar',
            'Oğuz Atay', 'Elif Şafak', 'Peyami Safa', 'Aziz Nesin', 'Reşat Nuri Güntekin',
            'Halide Edib Adıvar', 'Cemal Süreya', 'Necip Fazıl Kısakürek', 'Orhan Kemal', 'Attilâ İlhan',
            'Yahya Kemal Beyatlı', 'Sait Faik Abasıyanık', 'Can Yücel', 'Ömer Seyfettin', 'Ziya Gökalp',
            'Ahmet Ümit', 'Turgut Uyar', 'İhsan Oktay Anar', 'Kemal Tahir', 'Hüseyin Rahmi Gürpınar',
            'Rıfat Ilgaz', 'Cahit Sıtkı Tarancı', 'Yusuf Atılgan', 'Edip Cansever', 'Tevfik Fikret',
            'Zülfü Livaneli', 'Ahmet Arif', 'Oktay Rıfat', 'Fazıl Hüsnü Dağlarca', 'Hasan Ali Toptaş',
            'Mine Söğüt', 'Ahmet Altan', 'Sevgi Soysal', 'Latife Tekin', 'Murathan Mungan',
            'Buket Uzuner', 'Tahsin Yücel', 'Bilge Karasu', 'Ahmet Muhip Dıranas', 'Tarık Buğra',
            'Cahit Zarifoğlu', 'Melih Cevdet Anday', 'Behçet Necatigil', 'İsmet Özel', 'Ece Ayhan'
        ];

        foreach ($authors as $author) {
            Author::create([
                'name' => $author,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
