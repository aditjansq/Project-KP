<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeTransaksiToTransaksiPenjualansTable extends Migration
{
    public function up()
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->string('kode_transaksi')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->dropColumn('kode_transaksi');
        });
    }
}
