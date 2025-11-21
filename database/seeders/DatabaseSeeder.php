<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            Categories::class,
            Genres::class,
            Admin::class,
            Users::class,     
            Publishers::class,
            Authors::class,
            Languages::class,
            Books::class,
            AcquisitionSourcesSeeder::class, // <-- önce bu
            BookCopiesSeeder::class,         // <-- sonra bu
        ]);

        Notification::create([
            'user_id' => 1,
            'message' => 'Hoşgeldiniz. Bu uygulama ile kitapları takip edebilir, favori kitaplarınızı ve kategorilerinizi belirleyebilirsiniz.',
            'notification_type' => 'info',
            'read' => false,
        ]);
    }
}
