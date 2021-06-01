<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('cost_buy');
            $table->unsignedBigInteger('cost_activation');
            $table->unsignedBigInteger('cost_gaz');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('count_platform_subscribers');
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
        Schema::dropIfExists('platform_levels');
    }
}
