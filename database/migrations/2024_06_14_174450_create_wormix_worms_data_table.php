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
        Schema::create('wormix_worms_data', function (Blueprint $table) {
            $table->bigInteger('owner_id')->unsigned();

            $table->smallInteger('armor')->default(1);
            $table->smallInteger('attack')->default(1);

            $table->smallInteger('level')->default(1);
            $table->smallInteger('experience')->default(0);

            $table->smallInteger('hat')->default(0);

            $table->timestamps();
        });

        Schema::table('wormix_worms_data', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wormix_worms_data');
    }
};
