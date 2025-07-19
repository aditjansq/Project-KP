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
        Schema::table('transaksi_penjualan_pembayaran_details', function (Blueprint $table) {
            // Ubah tipe data kolom jumlah_pembayaran menjadi integer
            $table->integer('jumlah_pembayaran')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_penjualan_pembayaran_details', function (Blueprint $table) {
            // Kembalikan tipe data kolom jumlah_pembayaran ke tipe data sebelumnya (misalnya, decimal atau string)
            // Anda perlu menyesuaikan ini dengan tipe data sebelumnya yang Anda gunakan.
            // Contoh jika sebelumnya decimal:
            // $table->decimal('jumlah_pembayaran', 15, 2)->change();
            // Contoh jika sebelumnya string:
            // $table->string('jumlah_pembayaran')->change();
            $table->string('jumlah_pembayaran')->change(); // Ganti dengan tipe data asli jika bukan string
        });
    }
};
