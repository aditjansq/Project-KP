<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeItemDiscountValueToIntegerInItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('item_discount_value')->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Sesuaikan dengan tipe sebelumnya. Misalnya sebelumnya decimal
            $table->decimal('item_discount_value', 10, 2)->change();
        });
    }
}
