<?php

namespace App\Services;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Base\AuthorizeService;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UserService extends AuthorizeService
{
    use UploadTrait;

    /**
     * UserService constructor.
     *
     * @param Application $application
     *
     * @throws \App\Exceptions\Application\RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $application)
    {
        parent::__construct(new UserRepository($application));
    }

    /**
     * @return string
     */
    public function guard(): string
    {
        return BaseAppGuards::USER;
    }

    /**
     * @param array $data
     *
     * @return array|null
     * @throws \Throwable
     */
    public function register(array $data): ?array
    {
        return parent::register(
            [
                'first_name' => $data['first_name'] ?? null,
                'last_name'  => $data['last_name'] ?? null,
                'user_name'  => $data['user_name'],
                'password'   => $data['password'],
                'email'      => $data['email'],
                'google_id'  => $data['google_id'] ?? null,
            ]
        );
    }

    /**
     * @param string $searchField
     * @param string $filedValue
     *
     * @return array
     * @throws \ReflectionException
     */
    public function isEmailConfirmed(string $searchField, string $filedValue): array
    {
        $user = $this->model->findByOrFail($searchField, $filedValue);

        return ['confirmed' => $user->email_confirmed ?? false, 'email' => $user->email ?? null];
    }

    /**
     * @param array $data
     * @param int   $id
     *
     * @return void
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function changePassword(array $data, int $id): void
    {
        if (! Hash::check($data['password_current'], auth()->user()->password)) {
            throw new BadRequestException('Old password is incorrect');
        }

        if (strcmp($data['password_current'], $data['password_new']) === 0) {
            throw new BadRequestException('New password can`t be the same as current');
        }

        $user           = $this->model->findOrFail($id);
        $user->password = $data['password_new'];
        $user->save();
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

    public function changeUsername(User $user, string $username): User
    {
        if (! $user->username == null) {
            throw new AccessDeniedException(ErrorMessages::USERNAME_ALREADY_ATTEMPTED, Response::HTTP_FORBIDDEN);
        }

        $user->username = $username;
        $user->save();

        return $user->fresh();
    }

    /**
     * @param string $email
     *
     * @throws \App\Exceptions\Application\ApplicationException
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \ReflectionException
     */
    public function resendEmail(string $email): void
    {
        parent::resendEmail($email);
    }

    /**
     * @param $user
     * @param $device
     *
     * @return mixed
     */
    public function userActivity($request, $user)
    {
        return $user->activities()->create(
            [
                'user_id'    => $user->id,
                'last_login' => Carbon::now(),
                'device'     => $request->header('User-Agent'),
            ]
        );
    }
}
