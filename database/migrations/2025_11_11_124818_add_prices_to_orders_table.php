<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // nullable in case existing rows; بعدها نقدر نملأها بbackfill
            $table->decimal('unit_price', 10, 2)->nullable()->after('quantity');
            $table->decimal('total_price', 10, 2)->nullable()->after('unit_price');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'total_price']);
        });
    }
};
