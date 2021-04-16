<?php

namespace App\Services;

use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Exceptions\Model\NotFoundException;
use App\Mail\Admin\Auth\PasswordReset;
use App\Mail\Admin\Auth\SendLinkEmail;
use App\Models\Admin;
use App\Repositories\AdminRepository;
use App\Services\Base\AuthorizeService;
use App\Services\Base\BaseAppGuards;
use App\Traits\PasswordGenerator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class AdminService extends AuthorizeService
{
    use PasswordGenerator;

    /**
     * AdminService constructor.
     *
     * @param Application $application
     *
     * @throws \App\Exceptions\Application\RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $application)
    {
        parent::__construct(new AdminRepository($application), config('auth.authy.key_admin'));
    }

    public function guard(): string
    {
        return BaseAppGuards::ADMIN;
    }

    /**
     * @param string $filedValue
     * @param string $password
     * @param string|null $totp
     * @param string $searchField
     * @param bool $remember
     *
     * @return array
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \App\Exceptions\Model\NotFoundException
     * @throws \ReflectionException
     */
    public function authWithTOTP(
        string $filedValue,
        string $password,
        string $totp,
        string $searchField = 'email',
        bool $remember = false
    ): array {
        return parent::auth($filedValue, $password, $searchField, $remember, $totp);
    }


    public function setNewPassword(int $adminId, string $newPassword): Admin
    {
        return $this->model->setNewPassword($adminId, $newPassword);
    }

    public function sendLinkEmail(string $email): ?array
    {
        $user = $this->model->findByEmailOrFail($email);
        $token = \Str::random(\App\Models\Admin::SECRET_KEY_LENGTH);

        \App\Models\PasswordReset::query()->where('email', $user->email)->delete();
        \App\Models\PasswordReset::query()->create(compact('email', 'token'));

        Mail::to($email)->locale(Admin::DEFAULT_LANGUAGE)->queue(new SendLinkEmail($user->email, $token));

        return config('app.debug') ? compact('token') : null;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function sendNewPassword(string $email): bool
    {
        $user = $this->model->findByEmailOrFail($email);
        $newPassword = $this->generatePassword();

        Mail::to($email)->locale(\App\Models\Admin::DEFAULT_LANGUAGE)->queue(
            new PasswordReset($user->username ?? '', $newPassword)
        );

        $user->password = $newPassword;

        return $user->save();
    }

    /**
     * @param $phone
     * @param $id
     *
     * @throws \Exception
     */
    public function changePhone($phone, $id)
    {
        $user = $this->model->findOrFail($id);

        if ($user->authy2fa_enabled) {
            throw new  BadRequestException(ErrorMessages::TWO_FA_AUTH_ENABLED, Response::HTTP_NOT_ACCEPTABLE);
        }

        $user->phone = $phone;
        $user->save();
    }

    /**
     * @param $name
     * @param $id
     */
    public function changeName($name, $id)
    {
        $user = $this->model->findOrFail($id);

        $user->first_name = $name;
        $user->save();
    }

    public function confirmEmail(string $token): bool
    {
        $emailConfirmation = Admin\EmailConfirmation::query()->where('token', $token)->first();

        if (!$emailConfirmation) {
            throw new BadRequestException(ErrorMessages::INVALID_TOKEN);
        }
        $admin = Admin::query()->where('id', $emailConfirmation->admin_id)->first();

        if (!$admin) {
            throw new NotFoundException(ErrorMessages::ADMIN_NOT_EXIST);
        }

        $admin->email_confirmed = true;
        $admin->email = $emailConfirmation->email;
        $admin->save();

        return $emailConfirmation->delete();
    }
}
