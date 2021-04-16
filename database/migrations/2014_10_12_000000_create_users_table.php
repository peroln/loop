<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('google_id')->nullable();
                $table->boolean('google2fa_enabled')->default(false);
                $table->string('uuid');

                $table->string('email', 255)->unique()->index()->nullable();
                $table->boolean('email_confirmed')->default(false)->index();

                $table->string('user_name')->nullable()->unique()->index();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('phone')->unique()->nullable();
                $table->string('password', 255)->nullable();
                $table->string('photo')->nullable();

                $table->boolean('blocked')->default(false)->index();
                $table->text('block_reasons')->nullable();
                $table->boolean('active')->default(false)->index();
                $table->timestamp('last_login')->nullable()->default(null);
                $table->timestamp('last_activity')->nullable()->default(null);
                $table->timestamp('last_transaction')->nullable()->default(null);

                $table->rememberToken();
                $table->timestamps();
            }
        );

        if (config('app.debug')) {
            DB::table('users')->insert(
                [
                    [
                        'uuid'            => 'fb3337e4-26ff-4603-90a9-d6a90e31262f',
                        'email'           => 'user@gmail.com',
                        'user_name'        => 'TestUser',
                        'password'        => Hash::make('SDasdasbj213/213.'),
                        'first_name'      => 'Admin',
                        'last_name'       => 'Adminnij',
                        'active'          => true,
                        'email_confirmed' => true,
                    ],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
