<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiKreditDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi_kredit_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_penjualan_id')->constrained('transaksi_penjualans')->onDelete('cascade');
            $table->decimal('dp', 15, 2);
            $table->integer('tempo');
            $table->string('leasing');
            $table->decimal('angsuran_per_bulan', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_kredit_details');
    }
}
