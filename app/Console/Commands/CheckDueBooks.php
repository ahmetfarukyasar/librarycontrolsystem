<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowedBook;
use App\Models\Notification;
use Carbon\Carbon;

class CheckDueBooks extends Command
{
    protected $signature = 'books:check-due';
    protected $description = 'Check books due in 3 days and create notifications';

    public function handle()
    {
        $threeDaysFromNow = Carbon::now()->addDays(3);
        
        $dueBooks = BorrowedBook::whereNull('returned_at')
            ->where('status', 'borrowed')
            ->whereDate('return_date', '=', $threeDaysFromNow->toDateString())
            ->get();

        foreach ($dueBooks as $book) {
            Notification::create([
                'user_id' => $book->user_id,
                'message' => "'{$book->book->title}' kitabını teslim etmenize 3 gün kaldı.",
                'notification_type' => 'due_warning',
            ]);
        }
    }
}
