<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acquisition_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Satın Alım, Bağış vb.
            $table->string('description')->nullable();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acquisition_sources');
    }
};
