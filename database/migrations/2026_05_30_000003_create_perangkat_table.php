<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perangkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
            $table->string('jenis_perangkat');   // Laptop, Desktop, Printer, dll.
            $table->string('merek');
            $table->text('spesifikasi')->nullable();
            $table->text('keluhan');              // complaint description
            $table->string('kelengkapan')->nullable(); // accessories included
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkat');
    }
};
