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
        Schema::create('wormix_craft', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('internal_id')->unsigned()->unique('internal_idx');
            $table->bigInteger('upgrade_id')->unsigned()->unique('upgrade_idx');
            $table->bigInteger('prev_upgrade_id')->unsigned()->nullable();
            $table->integer('required_leve')->default(1);
            $table->integer('level');
            $table->json('reagents');
            $table->timestamps();
        });

        Schema::table('wormix_craft', function (Blueprint $table) {
            $table->foreign('prev_upgrade_id')
                ->references('upgrade_id')
                ->on('wormix_craft')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wormix_craft');
    }
};
