<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Mengganti nama tabel dari 'transaksi_pembelian' menjadi 'transaksi_pembelians'.
     */
    public function up(): void
    {
        // Pastikan tabel 'transaksi_pembelian' ada sebelum mencoba mengganti namanya
        if (Schema::hasTable('transaksi_pembelian')) {
            Schema::rename('transaksi_pembelian', 'transaksi_pembelians');
        }
    }

    /**
     * Batalkan migrasi.
     * Mengganti nama tabel kembali dari 'transaksi_pembelians' menjadi 'transaksi_pembelian'.
     */
    public function down(): void
    {
        // Pastikan tabel 'transaksi_pembelians' ada sebelum mencoba mengganti namanya kembali
        if (Schema::hasTable('transaksi_pembelians')) {
            Schema::rename('transaksi_pembelians', 'transaksi_pembelian');
        }
    }
};
