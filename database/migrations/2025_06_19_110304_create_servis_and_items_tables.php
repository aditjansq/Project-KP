<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServisAndItemsTables extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel servis dan items.
     *
     * @return void
     */
    public function up()
    {
        // Membuat tabel servis
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_servis')->unique();      // Kode Servis
            $table->unsignedBigInteger('mobil_id');      // Relasi ke tabel mobil
            $table->date('tanggal_servis');               // Tanggal Servis
            $table->string('metode_pembayaran');          // Metode Pembayaran
            $table->decimal('total_harga', 10, 2)->default(0);  // Total Harga
            $table->timestamps();

            $table->foreign('mobil_id')->references('id')->on('mobils')->onDelete('cascade');  // Foreign key untuk mobil_id
        });

        // Membuat tabel items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servis_id');      // Relasi ke tabel servis
            $table->string('item_name');                   // Nama Barang (Servis)
            $table->string('item_package');                // Kemasan
            $table->integer('item_qty')->default(1);       // Qty
            $table->decimal('item_price', 10, 2)->default(0);  // Harga Satuan
            $table->decimal('item_discount', 5, 2)->default(0); // Diskon (%)
            $table->decimal('item_discount_value', 10, 2)->default(0);  // Nilai Diskon
            $table->decimal('item_total', 10, 2)->default(0);  // Jumlah
            $table->date('service_date');                  // Tanggal Servis
            $table->timestamps();

            $table->foreign('servis_id')->references('id')->on('servis')->onDelete('cascade');  // Foreign key untuk servis_id
        });
    }

    /**
     * Menggulung perubahan yang dilakukan pada tabel servis dan items.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');  // Menghapus tabel items
        Schema::dropIfExists('servis'); // Menghapus tabel servis
    }
}
