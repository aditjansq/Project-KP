<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_pembelian', function (Blueprint $table) {
            $table->id(); // Primary Key ID

            $table->string('kode_transaksi')->unique(); // Kode unik transaksi pembelian
            $table->date('tanggal_transaksi'); // Tanggal transaksi terjadi

            // Foreign Key untuk Mobil
            $table->unsignedBigInteger('mobil_id');
            $table->foreign('mobil_id')->references('id')->on('mobils')->onDelete('cascade');

            // Foreign Key untuk Penjual
            $table->unsignedBigInteger('penjual_id');
            $table->foreign('penjual_id')->references('id')->on('penjuals')->onDelete('cascade');

            $table->bigInteger('harga_beli_mobil_final'); // Harga final beli mobil (gunakan bigInteger untuk menghindari masalah presisi desimal dengan uang, simpan dalam satuan terkecil, misal sen/rupiah tanpa koma)
            $table->string('status_pembayaran')->default('Belum Dibayar'); // Status pembayaran

            $table->string('bukti_pembayaran_file')->nullable(); // Nama file atau path bukti pembayaran, bisa null jika tidak ada bukti
            $table->text('keterangan')->nullable(); // Catatan tambahan

            // Foreign Key untuk User (siapa yang mencatat transaksi)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict'); // Gunakan restrict agar user tidak terhapus jika masih punya transaksi

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_pembelian');
    }
}
