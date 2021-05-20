<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandRefRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_ref_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('command_id')->constrained();
            $table->unsignedBigInteger('reference_id');
            $table->foreign('reference_id')->references('id')->on('wallets');
            $table->unsignedInteger('order');
            $table->foreignId('wallet_id')->constrained();
            $table->unsignedInteger('status')->default(1);
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
        Schema::dropIfExists('command_ref_requests');
    }
}
