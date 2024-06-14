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
        Schema::create('wormix_weapons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->boolean('is_starter')->default(0);

            $table->boolean('hide_in_shop')->default(0);

            $table->integer('price')->unsigned()->default(0);
            $table->integer('real_price')->unsigned()->default(0);

            $table->integer('required_friends')->unsigned()->default(0);
            $table->integer('required_level')->unsigned()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wormix_weapons');
    }
};
