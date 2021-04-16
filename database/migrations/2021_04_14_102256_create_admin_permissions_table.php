<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Permissions;

class CreateAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create(
			'admin_permission', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('admin_id');
			$table->unsignedBigInteger('permissions_id');
			$table->timestamps();
		}
		);

		$data  = [];
		$count = Permissions::count();

		for ($i = 1; $i <= $count; $i++) {
			$data[] = ['admin_id' => 1, 'permissions_id' => $i];
		}

		DB::table('admin_permission')
			->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::dropIfExists('admins_permissions');
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
