<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('staff', function (Blueprint $table) {
        DB::statement("ALTER TABLE staff MODIFY role ENUM('admin', 'barista', 'manager', 'host') DEFAULT 'barista'");
    });
}

public function down()
{
    Schema::table('staff', function (Blueprint $table) {
        DB::statement("ALTER TABLE staff MODIFY role ENUM('admin', 'barista', 'manager') DEFAULT 'barista'");
    });
}

};
