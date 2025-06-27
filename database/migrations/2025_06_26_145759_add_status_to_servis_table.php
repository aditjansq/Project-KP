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
        $table->enum('status', ['proses', 'selesai', 'batal'])->nullable()->after('tanggal_servis');            // di tabel 'servis' yang ingin Anda tempatkan kolom 'status' setelahnya.
            // Contoh: $table->enum('status', ['proses', 'selesai', 'batal'])->default('proses')->after('tanggal_servis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
