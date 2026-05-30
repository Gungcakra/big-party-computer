<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota')->unique()->nullable(); // auto-set after creation via ID
            $table->foreignId('perangkat_id')->constrained('perangkat')->cascadeOnDelete();
            $table->foreignId('teknisi_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('antri'); // antri | dalam_pengerjaan | selesai
            $table->text('diagnosa')->nullable();        // filled by teknisi
            $table->text('catatan')->nullable();
            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
