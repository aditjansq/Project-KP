<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_pembayaran_details', function (Blueprint $table) {
            // LANGKAH 1: Bersihkan data yang ada dari karakter non-digit
            // Ini akan memastikan "100.000.000" menjadi "100000000"
            DB::statement('UPDATE transaksi_pembayaran_details SET jumlah_pembayaran = REPLACE(REPLACE(jumlah_pembayaran, \'.\', \'\'), \',\', \'\')');

            // LANGKAH 2: Ubah tipe kolom menjadi BIGINT
            // Jika Anda ingin memastikan tidak ada nilai negatif, bisa tambahkan ->unsigned()
            $table->bigInteger('jumlah_pembayaran')->change();
            // Atau jika jumlah pembayaran tidak mungkin negatif:
            // $table->bigInteger('jumlah_pembayaran')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_pembayaran_details', function (Blueprint $table) {
            // Untuk rollback, ubah kembali ke tipe data yang Anda inginkan sebelumnya.
            // Misalnya, jika sebelumnya string:
            // $table->string('jumlah_pembayaran')->change();

            // Atau jika sebelumnya integer:
            $table->integer('jumlah_pembayaran')->change(); // Ini akan error lagi jika ada data > INT max
                                                            // Jika Anda yakin semua data akan muat di INT saat rollback:
                                                            // $table->integer('jumlah_pembayaran')->change();
                                                            // Jika tidak, Anda mungkin perlu memikirkan strategi rollback yang berbeda
                                                            // atau menerima bahwa rollback tidak akan mengembalikan data yang terlalu besar.
        });
    }
};
