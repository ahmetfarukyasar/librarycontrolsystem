<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Users extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ahmet Yılmaz',
                'email' => 'ahmet.yilmaz@example.com',
                'tcno' => '12345678901',
                'tel' => '5551234567',
                'password' => Hash::make('password123'),
                'address' => 'Kadıköy, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Ayşe Demir',
                'email' => 'ayse.demir@example.com',
                'tcno' => '12345678902',
                'tel' => '5551234568',
                'password' => Hash::make('password123'),
                'address' => 'Beşiktaş, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Mehmet Kaya',
                'email' => 'mehmet.kaya@example.com',
                'tcno' => '12345678903',
                'tel' => '5551234569',
                'password' => Hash::make('password123'),
                'address' => 'Üsküdar, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Zeynep Çelik',
                'email' => 'zeynep.celik@example.com',
                'tcno' => '12345678904',
                'tel' => '5551234570',
                'password' => Hash::make('password123'),
                'address' => 'Şişli, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Ali Öztürk',
                'email' => 'ali.ozturk@example.com',
                'tcno' => '12345678905',
                'tel' => '5551234571',
                'password' => Hash::make('password123'),
                'address' => 'Maltepe, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Fatma Arslan',
                'email' => 'fatma.arslan@example.com',
                'tcno' => '12345678906',
                'tel' => '5551234572',
                'password' => Hash::make('password123'),
                'address' => 'Bakırköy, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Mustafa Aydın',
                'email' => 'mustafa.aydin@example.com',
                'tcno' => '12345678907',
                'tel' => '5551234573',
                'password' => Hash::make('password123'),
                'address' => 'Ataşehir, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Esra Yıldız',
                'email' => 'esra.yildiz@example.com',
                'tcno' => '12345678908',
                'tel' => '5551234574',
                'password' => Hash::make('password123'),
                'address' => 'Pendik, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Hüseyin Şahin',
                'email' => 'huseyin.sahin@example.com',
                'tcno' => '12345678909',
                'tel' => '5551234575',
                'password' => Hash::make('password123'),
                'address' => 'Kartal, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Merve Koç',
                'email' => 'merve.koc@example.com',
                'tcno' => '12345678920', // TC numarası değiştirildi
                'tel' => '5551234576',
                'password' => Hash::make('password123'),
                'address' => 'Beylikdüzü, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Can Özkan',
                'email' => 'can.ozkan@example.com',
                'tcno' => '12345678911',
                'tel' => '5551234577',
                'password' => Hash::make('password123'),
                'address' => 'Tuzla, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Elif Yalçın',
                'email' => 'elif.yalcin@example.com',
                'tcno' => '12345678912',
                'tel' => '5551234578',
                'password' => Hash::make('password123'),
                'address' => 'Bağcılar, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Emre Aksoy',
                'email' => 'emre.aksoy@example.com',
                'tcno' => '12345678913',
                'tel' => '5551234579',
                'password' => Hash::make('password123'),
                'address' => 'Eyüp, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Selin Doğan',
                'email' => 'selin.dogan@example.com',
                'tcno' => '12345678914',
                'tel' => '5551234580',
                'password' => Hash::make('password123'),
                'address' => 'Sarıyer, İstanbul',
                'is_admin' => false
            ],
            [
                'name' => 'Burak Eren',
                'email' => 'burak.eren@example.com',
                'tcno' => '12345678915',
                'tel' => '5551234581',
                'password' => Hash::make('password123'),
                'address' => 'Beykoz, İstanbul',
                'is_admin' => false
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
