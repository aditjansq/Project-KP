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
        Schema::table('transaksi_kredit_details', function (Blueprint $table) {
            // Ubah tipe data kolom dp menjadi integer
            $table->integer('dp')->change();

            // Ubah tipe data kolom angsuran_per_bulan menjadi integer
            $table->integer('angsuran_per_bulan')->change();

            // Ubah tipe data kolom refund menjadi integer
            $table->integer('refund')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_kredit_details', function (Blueprint $table) {
            // Kembalikan tipe data kolom dp ke tipe data sebelumnya (misalnya, decimal atau string)
            // Anda perlu menyesuaikan ini dengan tipe data sebelumnya yang Anda gunakan.
            // Contoh jika sebelumnya decimal:
            // $table->decimal('dp', 15, 2)->change();
            // Contoh jika sebelumnya string:
            // $table->string('dp')->change();
            $table->string('dp')->change(); // Ganti dengan tipe data asli jika bukan string

            // Kembalikan tipe data kolom angsuran_per_bulan ke tipe data sebelumnya
            $table->string('angsuran_per_bulan')->change(); // Ganti dengan tipe data asli jika bukan string

            // Kembalikan tipe data kolom refund ke tipe data sebelumnya
            $table->string('refund')->change(); // Ganti dengan tipe data asli jika bukan string
        });
    }
};
