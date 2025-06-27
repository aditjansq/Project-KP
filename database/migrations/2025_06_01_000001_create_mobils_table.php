<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobils', function (Blueprint $table) {
            $table->id(); // Menambahkan kolom primary key id
            $table->string('kode_mobil')->unique();
            $table->string('jenis_mobil');
            $table->string('tipe_mobil');
            $table->string('merek_mobil');
            $table->year('tahun_pembuatan');
            $table->string('warna_mobil');
            $table->decimal('harga_mobil', 15, 2); // Untuk harga, menggunakan decimal agar lebih presisi
            $table->string('bahan_bakar');
            $table->string('transmisi');
            $table->string('nomor_polisi')->unique();
            $table->string('nomor_rangka')->unique();
            $table->string('nomor_mesin')->unique();
            $table->string('nomor_bpkb')->unique();
            $table->date('tanggal_masuk');
            $table->string('status_mobil');
            $table->string('ketersediaan');
            $table->date('masa_berlaku_pajak');
            $table->timestamps(); // Menambahkan created_at dan updated_at

            // Menambahkan indeks untuk mempercepat pencarian
            $table->index('kode_mobil');
            $table->index('nomor_polisi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobils');
    }
}
