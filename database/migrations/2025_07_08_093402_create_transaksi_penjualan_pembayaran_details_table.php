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
        Schema::create('transaksi_penjualan_pembayaran_details', function (Blueprint $table) {
            $table->id();
            // Foreign key menunjuk ke tabel 'transaksi_penjualans'
            // Menggunakan 'transaksi_id' seperti yang Anda minta
            $table->foreignId('transaksi_id')->constrained('transaksi_penjualans')->onDelete('cascade'); // Diperbarui: 'transaksi_penjualans'

            $table->string('metode_pembayaran_detail');
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->date('tanggal_pembayaran');
            $table->text('keterangan_pembayaran_detail')->nullable();
            $table->string('bukti_pembayaran_detail')->nullable(); // Path file bukti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualan_pembayaran_details');
    }
};
