<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // اسم قاعدة البيانات من env
        $database = env('DB_DATABASE');

        // 1) لو مافيش عمود guest_id — نضيفه مع الـ FK
        if (! Schema::hasColumn('sessions', 'guest_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            });
            return;
        }

        // 2) لو العمود موجود، نتأكد من وجود FK على العمود
        $fk = DB::selectOne("
            SELECT tc.constraint_name
            FROM information_schema.table_constraints tc
            JOIN information_schema.key_column_usage kcu
              ON tc.constraint_name = kcu.constraint_name
             AND tc.table_schema = kcu.table_schema
            WHERE tc.constraint_type = 'FOREIGN KEY'
              AND tc.table_schema = ?
              AND tc.table_name = 'sessions'
              AND kcu.column_name = 'guest_id'
            LIMIT 1
        ", [$database]);

        if (! $fk) {
            // نضيف الـ foreign key لو مش موجودة
            Schema::table('sessions', function (Blueprint $table) {
                // استخدم addForeign لتفادي تكرار تعريف العمود
                $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // نحذف الـ FK لو موجودة (لكن لا نحذف العمود لأنه قد يكون جزء من migration أساسي)
        $database = env('DB_DATABASE');

        $fk = DB::selectOne("
            SELECT tc.constraint_name
            FROM information_schema.table_constraints tc
            JOIN information_schema.key_column_usage kcu
              ON tc.constraint_name = kcu.constraint_name
             AND tc.table_schema = kcu.table_schema
            WHERE tc.constraint_type = 'FOREIGN KEY'
              AND tc.table_schema = ?
              AND tc.table_name = 'sessions'
              AND kcu.column_name = 'guest_id'
            LIMIT 1
        ", [$database]);

        if ($fk) {
            // نحاول نسقط الفوريجن باستخدام اسم العمود (laravel expects column name)
            Schema::table('sessions', function (Blueprint $table) {
                // dropForeign with array of column name
                $table->dropForeign(['guest_id']);
            });
        }
    }
};
