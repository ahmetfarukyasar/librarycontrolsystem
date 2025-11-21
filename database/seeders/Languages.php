<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Languages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'Türkçe', 'code' => 'tr'],
            ['name' => 'Azerice', 'code' => 'az'],
            ['name' => 'Albanaca', 'code' => 'sq'],
            ['name' => 'Arnavutça', 'code' => 'en'],
            ['name' => 'Bulgarca', 'code' => 'bg'],
            ['name' => 'Boşnakça', 'code' => 'bs'],
            ['name' => 'Çekçe', 'code' => 'cs'],
            ['name' => 'Danca', 'code' => 'da'],
            ['name' => 'Farsça', 'code' => 'fa'],
            ['name' => 'Flemenkçe', 'code' => 'nl'],
            ['name' => 'Frizce', 'code' => 'fy'],
            ['name' => 'Gürcüce', 'code' => 'ka'],
            ['name' => 'Hırvatça', 'code' => 'hr'],
            ['name' => 'İbranice', 'code' => 'he'],
            ['name' => 'İngilizce (Amerikan)', 'code' => 'en-US'],
            ['name' => 'İngilizce (Britanya)', 'code' => 'en-GB'],
            ['name' => 'İspanyolca', 'code' => 'es'],
            ['name' => 'Fransızca', 'code' => 'fr'],
            ['name' => 'Almanca', 'code' => 'de'],
            ['name' => 'İtalyanca', 'code' => 'it'],
            ['name' => 'Çince', 'code' => 'zh'],
            ['name' => 'Japonca', 'code' => 'ja'],
            ['name' => 'Rusça', 'code' => 'ru'],
            ['name' => 'Arapça', 'code' => 'ar'],
            ['name' => 'Portekizce', 'code' => 'pt'],
        ];

        Language::insert($languages);
    }
}
