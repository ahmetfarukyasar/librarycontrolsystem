<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookCopy;
use App\Models\Book;
use App\Models\Acquisition;
use App\Models\AcquisitionSource;

class BookCopiesSeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::all();
        $statuses = ['available', 'borrowed', 'reserved'];
        $conditions = ['yıpranmamış', 'az yıpranmış', 'yıpranmış', 'çok yıpranmış'];

        $totalCopies = 50;
        $bookCount = $books->count();

        if ($bookCount === 0) {
            return;
        }

        $copiesPerBook = intdiv($totalCopies, $bookCount);
        $extra = $totalCopies % $bookCount;
        $barcodeCounter = 100000;

        // Varsayılan acquisition source
        $acqSource = AcquisitionSource::first();
        if (!$acqSource) {
            // Acquisition source yoksa kopya ekleme
            return;
        }
        $acqSourceId = $acqSource->id;

        foreach ($books as $book) {
            $copiesForThisBook = $copiesPerBook + ($extra-- > 0 ? 1 : 0);
            for ($i = 0; $i < $copiesForThisBook; $i++) {
                $copy = BookCopy::create([
                    'book_id' => $book->id,
                    'barcode' => (string)($barcodeCounter++),
                    'status' => 'available',
                    'condition' => $conditions[array_rand($conditions)],
                    'shelf_location' => 'A-1-1-1-' . rand(1, 50)
                ]);

                // Acquisition kaydı ekle
                Acquisition::create([
                    'book_copy_id' => $copy->id,
                    'acquisition_date' => now()->subDays(rand(0, 365)),
                    'acquisition_source_id' => $acqSourceId,
                    'acquisition_cost' => rand(20, 200),
                    'acquisition_place' => 'İstanbul',
                    'acquisition_invoice' => 'INV-' . rand(1000, 9999),
                ]);
            }
        }
    }
}
