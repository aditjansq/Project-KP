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
        Schema::table('servis', function (Blueprint $table) {
            // Mengubah tipe data kolom total_biaya_keseluruhan menjadi integer
            // Pastikan untuk menambahkan after() jika Anda ingin menjaga urutan kolom,
            // atau gunakan change() jika kolom sudah ada dan Anda hanya ingin mengubah tipenya.
            // Jika data yang ada di kolom bukan angka atau mengandung karakter non-numerik,
            // Anda mungkin perlu membersihkannya terlebih dahulu sebelum menjalankan migrasi ini.
            $table->integer('total_biaya_keseluruhan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            // Mengembalikan tipe data kolom, misalnya ke string (varchar) jika itu adalah tipe sebelumnya
            $table->string('total_biaya_keseluruhan')->change();
        });
    }
};
