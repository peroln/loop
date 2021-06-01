<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_wallet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained();
            $table->foreignId('wallet_id')->constrained();
            $table->unsignedInteger('place');
            $table->unsignedInteger('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_wallet');
    }
}
