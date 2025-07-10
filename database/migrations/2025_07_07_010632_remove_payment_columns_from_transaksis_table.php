// database/migrations/YYYY_MM_DD_HHMMSS_remove_payment_columns_from_transaksis_table.php

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
            // Hapus kolom metode_pembayaran jika ada
            if (Schema::hasColumn('transaksis', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }
            // Hapus kolom dp_jumlah jika ada
            if (Schema::hasColumn('transaksis', 'dp_jumlah')) {
                $table->dropColumn('dp_jumlah');
            }
            // Hapus kolom bukti_pembayaran jika ada (jika ingin dipindah ke detail pembayaran)
            if (Schema::hasColumn('transaksis', 'bukti_pembayaran')) {
                $table->dropColumn('bukti_pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Mengembalikan kolom jika migrasi di-rollback
            // Sesuaikan tipe data dan constraint jika diperlukan, berdasarkan definisi asli kolom Anda
            if (!Schema::hasColumn('transaksis', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 255)->nullable()->after('total_harga'); // Sesuaikan posisi jika perlu
            }
            if (!Schema::hasColumn('transaksis', 'dp_jumlah')) {
                $table->decimal('dp_jumlah', 15, 2)->nullable()->after('metode_pembayaran'); // Sesuaikan posisi jika perlu
            }
            if (!Schema::hasColumn('transaksis', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran', 255)->nullable()->after('status_pembayaran'); // Sesuaikan posisi jika perlu
            }
        });
    }
};
