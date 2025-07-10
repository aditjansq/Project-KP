<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('transaksis');
    }

    public function down(): void
    {
        // Jika ingin bisa rollback, definisikan ulang struktur tabel di sini (opsional)
        Schema::create('transaksis', function ($table) {
            $table->id();
            $table->timestamps();
            // Tambahkan kolom lain jika ingin mendukung rollback
        });
    }
};
