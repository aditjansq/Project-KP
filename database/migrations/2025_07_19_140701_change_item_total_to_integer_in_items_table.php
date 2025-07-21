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
        Schema::table('items', function (Blueprint $table) {
            // Mengubah tipe data kolom item_total menjadi integer
            $table->integer('item_total')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Mengembalikan tipe data kolom, misalnya ke string (varchar) jika itu adalah tipe sebelumnya
            $table->string('item_total')->change();
        });
    }
};
