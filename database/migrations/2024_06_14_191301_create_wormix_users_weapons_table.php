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
        Schema::create('wormix_users_weapons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('owner_id')->unsigned();
            $table->bigInteger('weapon_id')->unsigned();
            $table->integer('count')->default(-1);
            $table->integer('expire_at')->default(-1);
            $table->timestamps();
        });

        Schema::table('wormix_users_weapons', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('weapon_id')->references('id')->on('wormix_weapons')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wormix_users_weapons');
    }
};
