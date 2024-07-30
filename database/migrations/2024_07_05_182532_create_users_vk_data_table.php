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
        Schema::create('users_vk_data', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name', 30)->nullable();
            $table->string('last_name', 30)->nullable();
            $table->string('nickname', 30)->nullable();
            $table->string('photo', 256)->nullable();
            $table->timestamps();
        });

        Schema::table('users_vk_data', function (Blueprint $table) {
           $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_vk_data');
    }
};
