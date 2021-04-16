<?php

namespace App\Models;

use App\Models\Admin\Permissions;
use App\Models\Helpers\Authy2FAInterface;
use App\Models\Helpers\BaseUsersModelInterface;
use App\Models\Helpers\JWTAuthModel;
use App\Services\Base\BaseAppGuards;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class Admin
 * @package App\Models
 *
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property bool $active
 * @property string $phone
 */
class Admin extends JWTAuthModel implements BaseUsersModelInterface
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'email',
        'password',
        'active',
        'first_name',
        'last_name',
        'phone',
    ];

    protected $hidden = [
        'password',
        'google2fa_secret',
        'remember_token',
        'super_admin',
    ];

    /**
     * Admin has many permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'admin_permission');
    }

    /**
     * Check if admin have permission
     *
     * @param string $permissionName
     * @param bool $admin_id
     *
     * @return mixed
     */
    public function hasPermission(string $permissionName, $admin_id = false): bool
    {
        if (!$admin_id) {
            $admin_id = auth()->guard(BaseAppGuards::ADMIN)->user()->id;
        }

        return $this->whereHas(
            'permissions',
            function ($query) use ($permissionName) {
                $query->where('name', '=', $permissionName);
            }
        )->where('id', '=', $admin_id)->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->super_admin;
    }
}
