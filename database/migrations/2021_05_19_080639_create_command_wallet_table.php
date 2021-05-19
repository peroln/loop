<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_wallet', function (Blueprint $table) {
            $table->foreignId('wallet_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('command_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('order');
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
        Schema::dropIfExists('command_wallet');
    }
}
