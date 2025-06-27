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
            // Menambahkan kolom bukti_pembayaran setelah kolom status_pembayaran
            // Kolom ini akan menyimpan path file bukti pembayaran
            $table->string('bukti_pembayaran')->after('status_pembayaran')->nullable();
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
            // Menghapus kolom bukti_pembayaran jika migrasi di-rollback
            $table->dropColumn('bukti_pembayaran');
        });
    }
};
