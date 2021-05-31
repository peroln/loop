<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('base58_id');
            $table->string('hex');
            $table->string('model_service');
            $table->foreignId('wallet_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('block_timestamp')->nullable();
            $table->string('blockNumber')->nullable();
            $table->string('ref_block_hash')->nullable();
            $table->unsignedInteger('energy_fee')->nullable();
            $table->unsignedInteger('energy_usage_total')->nullable();
            $table->unsignedInteger('fee_limit')->nullable();
            $table->unsignedInteger('call_value')->nullable();
            $table->unsignedBigInteger('expiration')->nullable();
            $table->string('timestamp')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
