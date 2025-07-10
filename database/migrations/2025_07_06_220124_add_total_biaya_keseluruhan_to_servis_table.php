<?php

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
        Schema::table('servis', function (Blueprint $table) {
            // Menambahkan kolom 'total_biaya_keseluruhan'
            // Menggunakan tipe data decimal untuk nilai moneter
            // Dengan 15 digit total dan 2 digit di belakang koma
            // Defaultnya diatur ke 0.00 dan bisa bernilai null (jika diperlukan, tapi untuk total lebih baik tidak null)
            $table->decimal('total_biaya_keseluruhan', 15, 2)->default(0.00)->after('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            // Menghapus kolom 'total_biaya_keseluruhan' jika migrasi di-rollback
            $table->dropColumn('total_biaya_keseluruhan');
        });
    }
};

