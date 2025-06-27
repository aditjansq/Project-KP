<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('penjuals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penjual')->unique(); // Harus unik dan tidak nullable
            $table->string('nama'); // Tidak nullable, karena nama harus diisi
            $table->date('tanggal_lahir'); // Tidak nullable, karena tanggal lahir harus diisi
            $table->string('pekerjaan'); // Tidak nullable, karena pekerjaan harus diisi
            $table->text('alamat'); // Tidak nullable, karena alamat harus diisi
            $table->string('no_telepon'); // Tidak nullable, karena no telepon harus diisi
            $table->timestamps(); // Akan menambahkan kolom created_at dan updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjuals');
    }
};
