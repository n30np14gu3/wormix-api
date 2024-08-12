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
        Schema::create('wormix_users_battle_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();

            $table->integer('battles_count')->unsigned()->default(10);

            $table->integer('mission_id')->default(-2);

            $table->integer('last_bott_fight_time')->unsigned()->default(0);
            $table->integer('last_battle_time')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::table('wormix_users_battle_info', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wormix_users_battle_info');
    }
};
