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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('metode_bayar', 20)->default('cash')->after('tanggal_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('metode_bayar');
        });
    }
};
