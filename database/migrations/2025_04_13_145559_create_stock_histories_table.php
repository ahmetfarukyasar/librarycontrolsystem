<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('copy_id')->constrained('book_copies')->onDelete('cascade');
            $table->string('event_type');
            $table->string('old_shelf')->nullable();
            $table->string('new_shelf')->nullable();
            $table->dateTimeTz('event_date')->useCurrent();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
