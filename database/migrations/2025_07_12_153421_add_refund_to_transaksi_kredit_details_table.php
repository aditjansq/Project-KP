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
        Schema::table('transaksi_kredit_details', function (Blueprint $table) {
            $table->decimal('refund', 15, 2)->nullable()->after('angsuran_per_bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_kredit_details', function (Blueprint $table) {
            $table->dropColumn('refund');
        });
    }
};
