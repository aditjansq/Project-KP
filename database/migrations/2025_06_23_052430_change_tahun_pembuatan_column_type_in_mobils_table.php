<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTahunPembuatanColumnTypeInMobilsTable extends Migration
{
    public function up()
    {
        Schema::table('mobils', function (Blueprint $table) {
            // Mengubah tipe data tahun_pembuatan menjadi YEAR
            $table->year('tahun_pembuatan')->change();
        });
    }

    public function down()
    {
        Schema::table('mobils', function (Blueprint $table) {
            // Mengembalikan tipe data jika migrasi dibatalkan
            $table->integer('tahun_pembuatan')->change();
        });
    }
}
