<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penjuals', function (Blueprint $table) {
            $table->dropColumn(['kartu_keluarga', 'slip_gaji']);
        });
    }

    public function down(): void
    {
        Schema::table('penjuals', function (Blueprint $table) {
            $table->string('kartu_keluarga')->nullable(); // atau sesuaikan tipe sebelumnya
            $table->string('slip_gaji')->nullable(); // sesuaikan juga jika sebelumnya file atau enum
        });
    }
};
