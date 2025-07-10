<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('transaksi_pembayaran_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id'); // Kolom foreign key
            $table->string('metode_pembayaran', 50);
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->date('tanggal_pembayaran')->nullable();
            $table->text('keterangan_pembayaran_detail')->nullable();
            $table->string('bukti_pembayaran_detail')->nullable();
            $table->timestamps();

            // Definisi Foreign Key: Merujuk ke tabel 'transaksi_pembelians'
            $table->foreign('transaksi_id')->references('id')->on('transaksi_pembelians')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayaran_details');
    }
};
