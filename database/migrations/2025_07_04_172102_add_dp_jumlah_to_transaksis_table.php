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
            // Tambahkan kolom dp_jumlah setelah metode_pembayaran
            // decimal(15, 2) cocok untuk mata uang: total 15 digit, 2 di antaranya di belakang koma.
            // nullable() berarti kolom ini boleh kosong, penting jika tidak semua transaksi memiliki DP.
            $table->decimal('dp_jumlah', 15, 2)->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus kolom dp_jumlah jika migrasi di-rollback
            $table->dropColumn('dp_jumlah');
        });
    }
};
