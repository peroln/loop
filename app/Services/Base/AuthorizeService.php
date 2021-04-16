<?php

namespace App\Services\Base;

use App\Exceptions\Application\AlreadyCreatedException;
use App\Exceptions\Application\ApplicationException;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\AccessDenyException;
use App\Exceptions\Http\BadRequestException;
use App\Exceptions\Model\NotFoundException;
use App\Mail\Auth\PasswordReset as PasswordResetEmail;
use App\Mail\Auth\Registration;
use App\Models\EmailConfirmation;
use App\Models\Helpers\BaseUsersModelInterface;
use App\Models\PasswordReset;
use App\Repositories\Base\Repository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class AuthorizeService extends BaseModelService
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    private ?Model $user;

    /**
     * Set model base guard
     *
     * @return string
     */
    abstract public function guard(): string;

    public function __construct(Repository $model)
    {
        parent::__construct($model);
    }

    /**
     * Authorize user
     *
     * @param string $filedValue
     * @param string $password
     * @param string $searchField
     * @param bool   $remember
     *
     * @param string $totp
     *
     * @return array
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \App\Exceptions\Model\NotFoundException
     * @throws \ReflectionException
     */
    public function auth(
        string $filedValue,
        string $password,
        string $searchField = 'email',
        bool $remember = false,
        string $totp = ''
    ): array {
        $this->user = $this->model->findBy($searchField, $filedValue)->first();

        if (! $this->user) {
            throw new NotFoundException(ErrorMessages::USER_NOT_EXIST);
        }

        if (! Hash::check($password, $this->user->password)) {
            throw new BadRequestException(ErrorMessages::INVALID_CREDENTIALS);
        }

        if (isset($this->user->email_confirmed) && ! $this->user->email_confirmed) {
            throw new AccessDenyException(ErrorMessages::EMAIL_NOT_CONFIRMED);
        }

        if (isset($this->user->blocked) && $this->user->blocked) {
            throw new AccessDenyException(ErrorMessages::USER_BLOCKED);
        }

        return $this->generateTokenAndLogin(
            [
                $searchField => $filedValue,
                'password'   => $password,
            ],
            $this->guard(),
            $remember
        );
    }

    /**
     * @param array       $credentials
     * @param string|null $guard
     * @param bool        $remember
     *
     * @return array
     * @throws BadRequestException
     */
    private function generateTokenAndLogin(array $credentials, string $guard = null, bool $remember = false): array
    {
        $ttlRemember = config('jwt.ttl');
        $ttlTemp     = config('jwt.ttl_temporary');
        $ttl         = ($remember === false) ? $ttlTemp : $ttlRemember;

        if (! $token = auth()->guard($guard)->setTtl((int) $ttl)->attempt($credentials)) {
            throw new BadRequestException(ErrorMessages::INVALID_CREDENTIALS);
        }

        $this->user->last_login = Carbon::now();
        $this->user->save();

        return ['token' => $token, 'user_data' => $this->user];
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        auth()->guard($this->guard())->logout();

        return true;
    }

    /**
     * @param array $data
     *
     * @return array|null
     * @throws \Throwable
     */
    public function register(array $data): ?array
    {
        $emailConfirmation = null;

        if ($this->newQuery()->where('user_name', $data['user_name'] ?? '')->orWhere('email', $data['email'] ?? '')
            ->first()) {
            throw new AlreadyCreatedException('User already exist');
        }

        if ($this->model->getModel() instanceof BaseUsersModelInterface) {
            DB::beginTransaction();
            try {
                $user = $this->model->create($data);

                if (! empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $emailConfirmation = EmailConfirmation::query()->where('email', $user->email)->first();

                    if ($emailConfirmation) {
                        $emailConfirmation->delete();
                    }

                    $confirmToken      = Str::random($this->model->getModelName()::CONFIRM_TOKEN);
                    $emailConfirmation = EmailConfirmation::query()->create(
                        [
                            'email' => $user->email,
                            'token' => $confirmToken,
                        ]
                    );

                    DB::commit();

                    $this->sendEmail($emailConfirmation, $user);
                } else {
                    throw new BadRequestException(ErrorMessages::SOMETHING_WENT_WRONG);
                }
            } catch (Throwable $e) {
                \Log::info($e->getMessage());
                DB::rollBack();

                throw new BadRequestException($e->getMessage());
            }

            return config('app.debug') ? ['email_confirm_token' => $emailConfirmation->token ?? ''] : null;
        }

        throw new ApplicationException("Base model must be implement BaseUsersModelInterface");
    }

    /**
     * Send registration email
     *
     * @param \App\Models\EmailConfirmation $emailConfirmation
     * @param                               $user
     *
     * @throws ApplicationException
     */
    protected function sendEmail(EmailConfirmation $emailConfirmation, $user): void
    {
        if ($this->model->getModel() instanceof BaseUsersModelInterface) {
            Mail::to($user->email)->locale($this->model->getModelName()::DEFAULT_LANGUAGE)->queue(
                new Registration(
                    $emailConfirmation,
                    $user,
                    $this->guard()
                )
            );
        } else {
            throw new ApplicationException("Base model must be implement BaseUsersModelInterface");
        }
    }

    /**
     * @param string $email
     *
     * @throws BadRequestException
     * @throws AccessDenyException
     * @throws ApplicationException
     * @throws \ReflectionException
     */
    protected function resendEmail(string $email): void
    {
        $user = $this->model->findByOrFail('email', $email);
        if (isset($this->user->email_confirmed) && $this->user->email_confirmed) {
            throw new BadRequestException("Email is already confirmed");
        }
        if (isset($this->user->blocked) && $this->user->blocked) {
            throw new AccessDenyException(ErrorMessages::USER_BLOCKED);
        }
        if ($user instanceof BaseUsersModelInterface) {
            $confirmToken      = Str::random($user::CONFIRM_TOKEN);
            $emailConfirmation = EmailConfirmation::query()->where('email', $email)->firstOrFail();
            $emailConfirmation->update(['token' => $confirmToken]);
            $emailConfirmation->refresh();
            $this->sendEmail($emailConfirmation, $user);
        } else {
            throw new ApplicationException("Base model must be implement BaseUsersModelInterface");
        }
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws AccessDenyException
     * @throws BadRequestException
     */
    public function tokenRefresh(Request $request): array
    {
        if (! $token = JWTAuth::setRequest($request)->getToken()) {
            throw new BadRequestException(ErrorMessages::TOKEN_NOT_PROVIDED);
        }

        try {
            $user = JWTAuth::authenticate();
        } catch (TokenExpiredException $e) {
            try {
                $newToken = JWTAuth::setRequest($request)->parseToken()->refresh();
                $user     = JWTAuth::setToken($newToken)->authenticate();
                if ($user && $user->active) {
                    return ['token' => $newToken];
                } else {
                    throw new AccessDenyException(ErrorMessages::TOKEN_EXPIRED);
                }
            } catch (JWTException $e) {
                throw new BadRequestException(ErrorMessages::TOKEN_INVALID);
            }
        }

        return ['token' => JWTAuth::getToken()->get()];
    }

    /**
     * @param string $email
     *
     * @return array|null
     * @throws AccessDenyException
     * @throws \ReflectionException
     */
    public function sendResetToken(string $email): ?array
    {
        $user = $this->model->findByOrFail('email', $email);

        if ($user->email_confirmed === false) {
            throw new AccessDenyException(ErrorMessages::EMAIL_NOT_CONFIRMED);
        }

        $token = md5(uniqid('', true));
        PasswordReset::query()->where('email', $email)->delete();
        PasswordReset::query()->insert(
            [
                ['email' => $user->email, 'token' => $token],
            ]
        );

        if ($this->model->getModel() instanceof BaseUsersModelInterface) {
            Mail::to($user->email)->locale($this->model->getModelName()::DEFAULT_LANGUAGE)->queue(
                new PasswordResetEmail(
                    $user->user_name
                    ?? '',
                    $token,
                    $this->guard()
                )
            );
        }

        return config('app.debug') ? ['password_reset_token' => $token] : null;
    }

    /**
     * @param string $token
     *
     * @return bool
     * @throws BadRequestException
     */
    public function checkEmailToken(string $token): bool
    {
        $resetPassword = PasswordReset::query()->where('token', $token)->first();

        if (! $resetPassword) {
            throw new BadRequestException(ErrorMessages::INVALID_TOKEN);
        }

        return true;
    }

    /**
     * @param string $token
     * @param string $newPassword
     *
     * @return bool
     * @throws NotFoundException
     */
    public function resetPassword(string $token, string $newPassword): bool
    {
        $resetPassword = PasswordReset::query()->where('token', $token)->first();

        if (! $resetPassword) {
            throw new NotFoundException(ErrorMessages::INVALID_TOKEN);
        }

        /**
         * @var \App\Models\Helpers\JWTAuthModel $user
         */
        $user = $this->newQuery()->where('email', $resetPassword->email)->first();

        if (! $user) {
            throw new NotFoundException(ErrorMessages::USER_NOT_EXIST);
        }

        $user->password = $newPassword;
        $user->active   = false;
        $user->save();
        $user->invalidateToken();

        return PasswordReset::query()->where('token', $token)->delete();
    }

    /**
     * @param string $token
     *
     * @return bool
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function confirmEmail(string $token): bool
    {
        $emailConfirmation = EmailConfirmation::query()->where('token', $token)->first();

        if (! $emailConfirmation) {
            throw new BadRequestException(ErrorMessages::INVALID_TOKEN);
        }

        $user = $this->newQuery()->where('email', $emailConfirmation->email)->first();

        if (! $user) {
            throw new NotFoundException(ErrorMessages::USER_NOT_EXIST);
        }

        $user->email_confirmed = true;
        $user->save();

        return $emailConfirmation->delete();
    }
}
