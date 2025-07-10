<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembayaranPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pembayaran_pembelian', function (Blueprint $table) {
            $table->id(); // Primary Key ID

            // Foreign Key untuk Transaksi Pembelian
            $table->unsignedBigInteger('transaksi_pembelian_id');
            $table->foreign('transaksi_pembelian_id')->references('id')->on('transaksi_pembelian')->onDelete('cascade');

            $table->string('metode_pembayaran'); // Metode pembayaran (e.g., 'Cash', 'Transfer Bank')
            $table->bigInteger('jumlah_pembayaran'); // Jumlah uang yang dibayarkan untuk detail ini
            $table->date('tanggal_pembayaran')->nullable(); // Tanggal pembayaran spesifik ini dilakukan (bisa null jika tidak selalu dicatat)
            $table->text('keterangan')->nullable(); // Catatan tambahan untuk detail pembayaran

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
        Schema::dropIfExists('detail_pembayaran_pembelian');
    }
}
