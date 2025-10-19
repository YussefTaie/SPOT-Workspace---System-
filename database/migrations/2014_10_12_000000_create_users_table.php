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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // المفتاح الأساسي
            $table->string('fullname'); // اسم المستخدم
            $table->string('phone_number')->unique(); // رقم الهاتف (غالبًا لازم يكون فريد)
            $table->string('email')->unique(); // البريد الإلكتروني
            $table->string('collage')->nullable(); // الكلية (ممكن تكون فاضية)
            $table->string('university')->nullable(); // الجامعة (ممكن تكون فاضية)
            $table->string('password'); // كلمة المرور (بتتخزن مشفرة)
            $table->timestamps(); // created_at و updated_at
        });
    }

 
};
