<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use App\Models\Category;

class Genres extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $genres = [
            ['genre_name' => 'Bilim Kurgu', 'category_id' => $categories->where('category_name', 'Bilim')->first()->id],
            ['genre_name' => 'Roman', 'category_id' => $categories->where('category_name', 'Edebiyat')->first()->id],
            ['genre_name' => 'Deneme', 'category_id' => $categories->where('category_name', 'Edebiyat')->first()->id],
            ['genre_name' => 'Felsefi Roman', 'category_id' => $categories->where('category_name', 'Felsefe')->first()->id],
            ['genre_name' => 'Teknoloji', 'category_id' => $categories->where('category_name', 'Teknoloji')->first()->id],
            ['genre_name' => 'Tarihsel Roman', 'category_id' => $categories->where('category_name', 'Tarih')->first()->id],
            ['genre_name' => 'Korku', 'category_id' => $categories->where('category_name', 'Edebiyat')->first()->id],
            ['genre_name' => 'Macera', 'category_id' => $categories->where('category_name', 'Edebiyat')->first()->id],
            ['genre_name' => 'Biyografi', 'category_id' => $categories->where('category_name', 'Tarih')->first()->id],
            ['genre_name' => 'Bilimsel Araştırma', 'category_id' => $categories->where('category_name', 'Bilim')->first()->id],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
