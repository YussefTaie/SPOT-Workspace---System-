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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->string('table_number')->nullable();
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->decimal('rate_per_hour', 10, 2)->default(0.00);
            $table->decimal('bill_amount', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }


};
