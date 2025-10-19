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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('college')->nullable();
            $table->string('university')->nullable();
            $table->string('password');
            $table->timestamp('registered_at')->useCurrent();
            $table->integer('visits_count')->default(0);
            $table->integer('total_time')->default(0); // بالدقائق
            $table->decimal('total_expenses', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }


};
