<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->string('coin')->default('trx');
            $table->string('address')->unique();
            $table->decimal('amount_transfers',20, 2)->default(0);
            $table->decimal('profit_referrals',20, 2)->default(0);
            $table->decimal('profit_reinvest',20, 2)->default(0);
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
        Schema::dropIfExists('wallets');
    }
}
