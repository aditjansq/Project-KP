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
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom 'servis_id' sebagai foreign key, nullable, setelah 'mobil_id'
            $table->foreignId('servis_id')->nullable()->after('mobil_id')->constrained('servis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menghapus foreign key constraint terlebih dahulu
            $table->dropConstrainedForeignId('servis_id');
            // Kemudian menghapus kolom 'servis_id'
            $table->dropColumn('servis_id');
        });
    }
};
