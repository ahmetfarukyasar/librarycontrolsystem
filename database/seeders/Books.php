<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class Books extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'book_name' => 'İnce Memed',
                'author_id' => 1, // Yaşar Kemal
                'page_count' => 450,
                'category_id' => 2,
                'isbn' => '9789750800001',
                'publisher_id' => 1,
                'publish_year' => 1955,
                'language_id' => 1,
                'description' => 'Yaşar Kemal\'in en ünlü romanı',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:6663013/wh:true/wi:220'
            ],
             [
                'book_name' => 'İnce Memed',
                'author_id' => 1, // Yaşar Kemal
                'page_count' => 450,
                'category_id' => 2,
                'isbn' => '9789750800021',
                'publisher_id' => 9,
                'publish_year' => 1959,
                'language_id' => 1,
                'description' => 'Yaşar Kemal\'in en ünlü romanı',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:6663013/wh:true/wi:220'
            ],
            [
                'book_name' => 'Kırmızı Saçlı Kadın',
                'author_id' => 2, // Orhan Pamuk
                'page_count' => 204,
                'category_id' => 2,
                'isbn' => '9789750800002',
                'publisher_id' => 1,
                'publish_year' => 2016,
                'language_id' => 1,
                'description' => 'Orhan Pamuk\'un son romanlarından',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:1145418/wh:true/wi:220'
            ],
            [
                'book_name' => 'Kuyucaklı Yusuf',
                'author_id' => 4, // Sabahattin Ali
                'page_count' => 220,
                'category_id' => 2,
                'isbn' => '9789750800003',
                'publisher_id' => 2,
                'publish_year' => 1937,
                'language_id' => 1,
                'description' => 'Türk edebiyatının klasiklerinden',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:1105919/wh:true/wi:220'
            ],
            [
                'book_name' => 'Kuyucaklı Yusuf',
                'author_id' => 4, // Sabahattin Ali
                'page_count' => 220,
                'category_id' => 2,
                'isbn' => '9789750800022',
                'publisher_id' => 7,
                'publish_year' => 1944,
                'language_id' => 1,
                'description' => 'Türk edebiyatının klasiklerinden',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:1105919/wh:true/wi:220'
            ],
            [
                'book_name' => 'Tutunamayanlar',
                'author_id' => 6, // Oğuz Atay
                'page_count' => 724,
                'category_id' => 2,
                'isbn' => '9789750800004',
                'publisher_id' => 3,
                'publish_year' => 1972,
                'language_id' => 1,
                'description' => 'Modern Türk edebiyatının başyapıtlarından',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11462655/wh:true/wi:220'
            ],
            [
                'book_name' => 'Tutunamayanlar',
                'author_id' => 6, // Oğuz Atay
                'page_count' => 724,
                'category_id' => 2,
                'isbn' => '9789750800023',
                'publisher_id' => 5,
                'publish_year' => 1979,
                'language_id' => 1,
                'description' => 'Modern Türk edebiyatının başyapıtlarından',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11462655/wh:true/wi:220'
            ],
            [
                'book_name' => 'Aşk',
                'author_id' => 7, // Elif Şafak
                'page_count' => 420,
                'category_id' => 2,
                'isbn' => '9789750800005',
                'publisher_id' => 5,
                'publish_year' => 2009,
                'language_id' => 1,
                'description' => 'Doğu ile Batı arasında bir aşk hikayesi',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:1153650/wh:true/wi:220'
            ],
            [
                'book_name' => 'Serenad',
                'author_id' => 31, // Zülfü Livaneli
                'page_count' => 481,
                'category_id' => 2,
                'isbn' => '9789750800006',
                'publisher_id' => 6,
                'publish_year' => 2011,
                'language_id' => 1,
                'description' => 'Zülfü Livaneli\'den etkileyici bir roman',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11483001/wh:true/wi:220'
            ],
            [
                'book_name' => 'Saatleri Ayarlama Enstitüsü',
                'author_id' => 5, // Ahmet Hamdi Tanpınar
                'page_count' => 392,
                'category_id' => 2,
                'isbn' => '9789750800007',
                'publisher_id' => 7,
                'publish_year' => 1961,
                'language_id' => 1,
                'description' => 'Ahmet Hamdi Tanpınar\'ın başyapıtı',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11964184/wh:true/wi:220'
            ],
            [
                'book_name' => 'Yalnızız',
                'author_id' => 8, // Peyami Safa
                'page_count' => 384,
                'category_id' => 2,
                'isbn' => '9789750800008',
                'publisher_id' => 8,
                'publish_year' => 1951,
                'language_id' => 1,
                'description' => 'Peyami Safa\'dan psikolojik bir roman',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11685699/wh:true/wi:220'
            ],
            [
                'book_name' => 'Çalıkuşu',
                'author_id' => 10, // Reşat Nuri Güntekin
                'page_count' => 423,
                'category_id' => 2,
                'isbn' => '9789750800009',
                'publisher_id' => 9,
                'publish_year' => 1922,
                'language_id' => 1,
                'description' => 'Reşat Nuri Güntekin\'in en bilinen romanı',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:12015321/wh:true/wi:220'
            ],
            [
                'book_name' => 'Kürk Mantolu Madonna',
                'author_id' => 4, // Sabahattin Ali
                'page_count' => 160,
                'category_id' => 2,
                'isbn' => '9789750800010',
                'publisher_id' => 2,
                'publish_year' => 1943,
                'language_id' => 1,
                'description' => 'Sabahattin Ali\'nin en çok okunan romanı',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:1207631/wh:true/wi:220'
            ],
            [
                'book_name' => 'Sinekli Bakkal',
                'author_id' => 11, // Halide Edib Adıvar
                'page_count' => 416,
                'category_id' => 2,
                'isbn' => '9789750800011',
                'publisher_id' => 10,
                'publish_year' => 1936,
                'language_id' => 1,
                'description' => 'Halide Edib Adıvar\'dan klasik bir eser',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11469493/wh:true/wi:220'
            ],
            [
                'book_name' => 'İstanbul Hatırası',
                'author_id' => 21, // Ahmet Ümit
                'page_count' => 472,
                'category_id' => 2,
                'isbn' => '9789750800012',
                'publisher_id' => 11,
                'publish_year' => 2010,
                'language_id' => 1,
                'description' => 'Ahmet Ümit\'ten polisiye roman',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:10083418/wh:true/wi:220'
            ],
            
            [
                'book_name' => 'İçimizdeki Şeytan',
                'author_id' => 4, // Sabahattin Ali
                'page_count' => 232,
                'category_id' => 2,
                'isbn' => '9789750800014',
                'publisher_id' => 2,
                'publish_year' => 1940,
                'language_id' => 1,
                'description' => 'Sabahattin Ali\'den psikolojik roman',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:7865873/wh:true/wi:220'
            ],
            [
                'book_name' => 'Huzur',
                'author_id' => 5, // Ahmet Hamdi Tanpınar
                'page_count' => 256,
                'category_id' => 2,
                'isbn' => '9789750800015',
                'publisher_id' => 7,
                'publish_year' => 1949,
                'language_id' => 1,
                'description' => 'Ahmet Hamdi Tanpınar\'dan bir başyapıt',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11893657/wh:true/wi:220'
            ],
           
            [
                'book_name' => 'Fatih-Harbiye',
                'author_id' => 8, // Peyami Safa
                'page_count' => 192,
                'category_id' => 2,
                'isbn' => '9789750800017',
                'publisher_id' => 8,
                'publish_year' => 1931,
                'language_id' => 1,
                'description' => 'Peyami Safa\'dan toplumsal roman',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11689399/wh:true/wi:220'
            ],
           
            [
                'book_name' => 'Aylak Adam',
                'author_id' => 28, // Yusuf Atılgan
                'page_count' => 160,
                'category_id' => 2,
                'isbn' => '9789750800019',
                'publisher_id' => 14,
                'publish_year' => 1959,
                'language_id' => 1,
                'description' => 'Yusuf Atılgan\'dan modern bir roman',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11438831/wh:true/wi:220'
            ],
            [
                'book_name' => 'Anayurt Oteli',
                'author_id' => 28, // Yusuf Atılgan
                'page_count' => 144,
                'category_id' => 2,
                'isbn' => '9789750800020',
                'publisher_id' => 14,
                'publish_year' => 1973,
                'language_id' => 1,
                'description' => 'Yusuf Atılgan\'dan psikolojik roman',
                'book_cover' => 'https://img.kitapyurdu.com/v1/getImage/fn:11513182/wh:true/wi:220'
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
