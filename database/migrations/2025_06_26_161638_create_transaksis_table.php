<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_transaksis_table.php
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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id(); // Primary key auto-incrementing
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade'); // Foreign key ke tabel mobils
            $table->foreignId('pembeli_id')->constrained('pembelis')->onDelete('cascade'); // Foreign key ke tabel pembelis
            $table->foreignId('penjual_id')->nullable()->constrained('penjuals')->onDelete('cascade'); // Foreign key ke tabel penjuals (nullable jika tidak selalu ada)
            $table->date('tanggal_transaksi');
            $table->decimal('total_harga', 15, 2); // Contoh kolom harga
            // Tambahkan kolom-kolom lain yang Anda butuhkan di tabel transaksi Anda
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
