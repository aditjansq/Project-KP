<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksi_penjualans MODIFY metode_pembayaran ENUM('non_kredit', 'kredit') NOT NULL");
    }

    public function down(): void
    {
        // Misalnya sebelumnya metode_pembayaran berupa string biasa, kembalikan ke VARCHAR
        DB::statement("ALTER TABLE transaksi_penjualans MODIFY metode_pembayaran VARCHAR(50) NOT NULL");
    }
};
