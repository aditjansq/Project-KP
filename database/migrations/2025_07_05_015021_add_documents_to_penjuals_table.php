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
        Schema::table('penjuals', function (Blueprint $table) {
            $table->string('ktp_pasangan')->nullable()->after('pekerjaan');
            $table->string('kartu_keluarga')->nullable()->after('ktp_pasangan');
            $table->string('slip_gaji')->nullable()->after('kartu_keluarga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjuals', function (Blueprint $table) {
            $table->dropColumn(['ktp_pasangan', 'kartu_keluarga', 'slip_gaji']);
        });
    }
};
