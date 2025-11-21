<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowed_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('copy_id')->constrained('book_copies')->onDelete('cascade');
            $table->dateTimeTz('purchase_date')->useCurrent();
            $table->dateTimeTz('return_date');
            $table->dateTimeTz('returned_at')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue'])->default('borrowed');
            $table->integer('delay_day')->default(0);
            $table->integer('late_fee')->default(0);
            $table->integer('extension_count')->default(0);
            $table->text('notes')->nullable();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowed_books');
    }
};
