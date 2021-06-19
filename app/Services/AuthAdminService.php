<?php


namespace App\Services;


use App\Http\Requests\Admin\UserLogin2FARequest;
use App\Http\Requests\Admin\UserSwitcher2FARequest;
use App\Models\User;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;
use PragmaRX\Google2FA\Google2FA;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthAdminService
{
    public function login(UserLogin2FARequest $request)
    {

        if (Auth::guard('users')->attempt([
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ])) {
            $user = Auth::guard('users')->user();
            if ($user->google2fa) {
                if (!$request->filled('secret')) {
                    return response()->json(['secret' => 'Secret code is required'], 422);
                }
                if (!$user->google2fa_secret) {
                    return response()->json(['error' => 'google2fa_secret is invalid'], 401);
                }
                $res = $this->login2FA($user->google2fa_secret, $request->input('secret'));
                if (!$res) {
                    return response()->json(['error' => 'Secret code is invalid'], 401);
                }
            }
            return $this->getRegisterUserToken($user);
        }
        if (!auth()->check()) {
            return response()->json(['error' => 'Authentication is failure'], 401);
        }

    }

    /**
     * @param  User  $user
     *
     * @return JsonResponse
     */
    private function getRegisterUserToken(User $user): JsonResponse
    {
        try {
            $token = Auth::guard('admins')->fromUser($user);

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 502);
        }
        $expires_in = $this->getExpiresTime();

        return response()->json(compact('token', 'expires_in'), 200);
    }

    /**
     * @param  string  $secret
     *
     * @return bool|int
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    private function login2FA(string $google2fa_secret, string $secret)
    {
        $google2fa = new Google2FA();
        return $google2fa->verifyKey($google2fa_secret, $secret);

    }

    /**
     * @return int
     */
    private function getExpiresTime(): int
    {
        return Auth::guard('admins')->factory()->getTTL() * 60 * 1000;
    }

    /**
     * @param  UserSwitcher2FARequest  $request
     *
     * @return JsonResponse
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function switcher2FA(UserSwitcher2FARequest $request): JsonResponse
    {
        $user = Auth::guard('admins')->user();
        if ($request->isMethod('get')) {
            if (!$user->google2fa) {
                return $this->createQRCode($user, $request->input('qr_size', 400));
            } else {
                return response()->json(['error' => 'You cannot do this'], 401);
            }
        }
        if ($request->isMethod('post')) {
            if ($request->filled('secret')) {
                if ($user->google2fa) {
                    if ($this->login2FA($user->google2fa_secret, $request->input('secret'))) {
                        $user->google2fa = false;
                        $user->save();
                        return response()->json('2FA is turn off');
                    } else {
                        return response()->json(['error' => 'Secret code is invalid'], 401);
                    }
                } else {
                    if ($this->login2FA($user->google2fa_secret, $request->input('secret'))) {
                        $user->google2fa = true;
                        $user->save();
                        return $this->getRegisterUserToken($user);
                    } else {
                        return response()->json(['error' => 'Secret code is invalid'], 401);
                    }
                }

            } else {
                return response()->json(['error' => 'Secret code is required'], 401);
            }
        }
        return response()->json(['error' => 'Invalid condition'], 401);

    }

    /**
     * @param  User  $user
     * @param  int   $qr_size
     *
     * @return JsonResponse
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    private function createQRCode(User $user, int $qr_size = 400)
    {
        $google2fa              = new Google2FA();
        $user->google2fa_secret = $google2fa->generateSecretKey();
        $user->save();
        $base_64_qrcode_image = $this->getImageQRCode($user, $google2fa, $qr_size);
        return response()->json(compact('base_64_qrcode_image'));
    }

    /**
     * @param  User       $user
     * @param  Google2FA  $google2fa
     * @param  int        $size
     *
     * @return string
     */
    private function getImageQRCode(User $user, Google2FA $google2fa, int $size = 400): string
    {
        $g2faUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );
        $writer  = new Writer(
            new ImageRenderer(
                new RendererStyle($size),
                new ImagickImageBackEnd()
            )
        );
        return base64_encode($writer->writeString($g2faUrl));
    }
}
