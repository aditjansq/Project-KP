<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToIntegerInItemsAndServisTables extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Ubah kolom item_price dan item_discount jadi integer
            $table->integer('item_price')->change();
            $table->integer('item_discount')->change();
        });

        Schema::table('servis', function (Blueprint $table) {
            // Ubah kolom total_harga jadi integer
            $table->integer('total_harga')->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Sesuaikan dengan tipe data sebelumnya jika perlu rollback
            $table->decimal('item_price', 8, 2)->change();
            $table->decimal('item_discount', 8, 2)->change();
        });

        Schema::table('servis', function (Blueprint $table) {
            $table->decimal('total_harga', 10, 2)->change();
        });
    }
}
