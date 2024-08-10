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
        Schema::create('wormix_login_sequence', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->date('last_login')->nullable();

            $table->smallInteger('bonus_type')->default(0);
            $table->smallInteger('bonus_count')->default(0);

            $table->smallInteger('login_sequence')->unsigned()->default(1);
            $table->boolean('gift_accepted')->default(false);

            $table->timestamps();
        });

        Schema::table('wormix_login_sequence', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wormix_login_sequence');
    }
};
