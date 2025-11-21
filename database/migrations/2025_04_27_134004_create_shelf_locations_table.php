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
        Schema::create('shelf_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_copy_id')->constrained('book_copies')->onDelete('cascade');
            $table->enum('block', ['A', 'B']);
            $table->integer('floor')->comment('0: 0, 1: 1, 2: 2');
            $table->integer('row')->comment('Sıra numarası');
            $table->integer('shelf')->comment('Raf numarası');
            $table->integer('position')->comment('Raftaki pozisyon');
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();

            // Benzersiz konum için index
            $table->unique(['block', 'floor', 'row', 'shelf', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelf_locations');
    }
};
