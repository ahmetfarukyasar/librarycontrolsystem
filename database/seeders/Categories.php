<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class Categories extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::create(['category_name' => 'Bilim']);
        Category::create(['category_name' => 'Edebiyat']);
        Category::create(['category_name' => 'Felsefe']);
        Category::create(['category_name' => 'Teknoloji']);
        Category::create(['category_name' => 'Tarih']);
        Category::create(['category_name' => 'Sanat']);
        Category::create(['category_name' => 'Psikoloji']);
        Category::create(['category_name' => 'Ekonomi']);
        Category::create(['category_name' => 'Siyaset']);
        Category::create(['category_name' => 'Spor']);
        Category::create(['category_name' => 'Müzik']);
        Category::create(['category_name' => 'Sinema']);
        Category::create(['category_name' => 'Sağlık']);
        Category::create(['category_name' => 'Eğitim']);
        Category::create(['category_name' => 'Din']);
        Category::create(['category_name' => 'Mimari']);
        Category::create(['category_name' => 'Coğrafya']);
        Category::create(['category_name' => 'Biyografi']);
        Category::create(['category_name' => 'Gezi']);
        Category::create(['category_name' => 'Yemek']);
    }
}
