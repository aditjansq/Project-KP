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
        Schema::table('pembelis', function (Blueprint $table) {
            // Kolom untuk KTP Suami/Istri (opsional, jadi nullable)
            $table->string('ktp_pasangan')->nullable()->after('no_telepon');

            // Kolom untuk Kartu Keluarga (wajib, bisa hapus nullable() jika memang tidak boleh kosong)
            $table->string('kartu_keluarga')->nullable()->after('ktp_pasangan');

            // Kolom untuk Slip Gaji (wajib, bisa hapus nullable() jika memang tidak boleh kosong)
            $table->string('slip_gaji')->nullable()->after('kartu_keluarga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelis', function (Blueprint $table) {
            // Menghapus kolom-kolom saat migrasi di-rollback
            $table->dropColumn(['ktp_pasangan', 'kartu_keluarga', 'slip_gaji']);
        });
    }
};
