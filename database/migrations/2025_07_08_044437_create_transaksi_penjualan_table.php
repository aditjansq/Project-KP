<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPenjualanTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel mobil dan pembeli
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');
            $table->foreignId('pembeli_id')->constrained('pembelis')->onDelete('cascade');

            // Metode pembayaran: tunai / kredit
            $table->enum('metode_pembayaran', ['tunai', 'kredit']);

            // Harga asli dan hasil negosiasi
            $table->decimal('total_harga', 15, 2);        // harga asli dari mobil
            $table->decimal('harga_negosiasi', 15, 2);    // harga yang disepakati

            // Tanggal transaksi
            $table->date('tanggal_transaksi');

            // Status transaksi
            $table->enum('status', ['dp', 'lunas', 'belum lunas'])->default('dp');

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
}
