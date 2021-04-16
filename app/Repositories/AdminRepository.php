<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Base\Repository;
use Illuminate\Database\Eloquent\Model;

class AdminRepository extends Repository
{
    public function model(): string
    {
        return Admin::class;
    }

    /**
     * @param int    $adminId
     * @param string $newPassword
     *
     * @return Admin|null|Model
     */
    public function setNewPassword(int $adminId, string $newPassword): Admin
    {
        $admin = $this->model->newQuery()->findOrFail($adminId);
        $admin->password = $newPassword;
        $admin->save();

        return $admin;
    }

    public function findByEmailOrFail(string $email): Model
    {
        return $this->model->newQuery()->where('email', $email)->firstOrFail();
    }
}
