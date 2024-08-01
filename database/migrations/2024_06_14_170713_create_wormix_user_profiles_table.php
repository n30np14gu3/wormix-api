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
        Schema::create('wormix_user_profiles', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();

            $table->integer('money')->unsigned()->default(450)->comment('fuses');
            $table->integer('real_money')->unsigned()->default(3)->comment('rubies');

            $table->integer('rating')->unsigned()->default(0)->comment('user rating');
            $table->integer('reaction_rate')->unsigned()->default(0)->comment('user reaction rate');
            $table->timestamps();
        });
        Schema::table('wormix_user_profiles', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wormix_user_profiles');
    }
};
