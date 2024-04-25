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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname',20);
            $table->string('name',20);
            $table->string('sex',10);
            $table->tinyinteger('age');
            $table->string('email')->charset("utf8")->unique();
            $table->string('password');
            $table->string('image_icon',100)->nullable();
            $table->string('status',20)->default('active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
