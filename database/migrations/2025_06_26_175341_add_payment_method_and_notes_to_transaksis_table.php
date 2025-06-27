<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom metode_pembayaran setelah kolom total_harga
            $table->string('metode_pembayaran')->after('total_harga')->nullable();
            // Menambahkan kolom keterangan setelah kolom metode_pembayaran
            $table->text('keterangan')->after('metode_pembayaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('metode_pembayaran');
            $table->dropColumn('keterangan');
        });
    }
};
