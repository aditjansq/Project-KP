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
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom 'tempo_angsuran' sebagai integer, nullable, setelah 'metode_pembayaran'
            // Kolom ini akan menyimpan durasi angsuran dalam tahun (misal: 1, 2, 3, 4, 5 tahun)
            $table->integer('tempo_angsuran')->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menghapus kolom 'tempo_angsuran' jika migrasi di-rollback
            $table->dropColumn('tempo_angsuran');
        });
    }
};

