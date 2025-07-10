<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi_pembelians', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran_file');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_pembelians', function (Blueprint $table) {
            $table->string('bukti_pembayaran_file')->nullable(); // Sesuaikan jika sebelumnya bukan string
        });
    }
};

