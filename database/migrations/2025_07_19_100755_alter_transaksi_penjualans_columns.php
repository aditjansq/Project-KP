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
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            // Ubah tipe data kolom total_harga menjadi integer
            $table->integer('total_harga')->change();

            // Ubah tipe data kolom harga_negosiasi menjadi integer
            $table->integer('harga_negosiasi')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            // Kembalikan tipe data kolom total_harga ke tipe data sebelumnya (misalnya, decimal atau string jika itu yang Anda gunakan)
            // Contoh jika sebelumnya decimal:
            // $table->decimal('total_harga', 15, 2)->change();
            // Contoh jika sebelumnya string:
            // $table->string('total_harga')->change();
            // Anda perlu menyesuaikan ini dengan tipe data sebelumnya yang Anda gunakan.
            $table->string('total_harga')->change(); // Ganti dengan tipe data asli jika bukan string

            // Kembalikan tipe data kolom harga_negosiasi ke tipe data sebelumnya
            // Contoh jika sebelumnya decimal:
            // $table->decimal('harga_negosiasi', 15, 2)->change();
            // Contoh jika sebelumnya string:
            // $table->string('harga_negosiasi')->change();
            $table->string('harga_negosiasi')->change(); // Ganti dengan tipe data asli jika bukan string
        });
    }
};
