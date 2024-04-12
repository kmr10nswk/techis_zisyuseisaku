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
        Schema::table('users', function (Blueprint $table) {
            $table->string('comment',400)->after('image_icon')->nullable();
            $table->string('oneword',40)->after('image_icon')->nullable();
            $table->dropColumn('age');
            $table->dropColumn('sex');
            $table->string('session_style',20)->after('name');
            $table->string('gmpl',5)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('oneword');
            $table->dropColumn('comment');
            $table->tinyinteger('age')->after('name');
            $table->string('sex',20)->after('name');
            $table->dropColumn('gmpl');
            $table->dropColumn('session_style');
        });
    }
};
