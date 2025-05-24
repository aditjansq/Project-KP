<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mobil')->unique();
            $table->string('tipe_mobil');
            $table->string('merek_mobil');
            $table->year('tahun_pembuatan');
            $table->string('warna_mobil');
            $table->decimal('harga_mobil', 15, 2);
            $table->string('bahan_bakar');
            $table->string('nomor_polisi')->unique();
            $table->string('nomor_rangka')->unique();
            $table->string('nomor_mesin')->unique();
            $table->string('nomor_bpkb')->unique();
            $table->date('tanggal_masuk');
            $table->enum('status_mobil', ['baru', 'bekas']);
            $table->enum('stok', ['ada', 'tidak']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mobils');
    }
};
