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
        Schema::create('pembelis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembeli')->unique();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('pekerjaan');
            $table->text('alamat');
            $table->string('no_telepon');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembeli');
    }
};
