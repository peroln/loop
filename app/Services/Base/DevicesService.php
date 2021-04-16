<?php

declare(strict_types=1);

namespace App\Services\Base;

use App\Exceptions\Application\ApplicationException;
use App\Exceptions\Http\BadRequestException;
use App\Jobs\QueuesNames;
use App\Models\Helpers\DeviceCheckInterface;
use App\Models\Helpers\JWTAuthModel;
use App\Traits\CodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class DevicesService extends BaseModelService
{
    use CodeGenerator;

    /**
     * @param string $deviceId
     *
     * @return array
     */
    abstract protected function prepareDataForSave(string $deviceId): array;

    /**
     * Get device user
     *
     * @return \App\Models\Helpers\JWTAuthModel
     */
    abstract protected function getUser(): JWTAuthModel;

    /**
     * Check users device exist, if not, create a new users device instance and send email notification
     *
     * @param Request $request
     * @param string  $requestFieldName
     *
     * @return void
     * @throws \Exception
     */
    public function checkDeviceByDeviceId(Request $request, string $requestFieldName = 'deviceId'): void
    {
        $request->validate([$requestFieldName => 'required|string|min:3']);
        $device = $this->getDevice($request->input($requestFieldName));
        if (!$device) {
            $hasDevices = (bool) $this->hasDevices(); // check if current authorized user has device
            ['code' => $code, 'hash' => $hash] = $this->generateCode();
            $device = $this->createNewDevice($request, $hash, !$hasDevices);
            $user = $this->getUser();
            $this->sendNewDeviceEmail(
                $user->email,
                $user->username ?? $user->email,
                $device,
                $request->server->get('HTTP_X_FORWARDED_FOR') ?? '0.0.0.0',
                $hasDevices ? $code : null
            );
        }
    }

    /**
     * Create a new users device instance
     *
     * @param Request     $request
     * @param string|null $code
     * @param bool        $verify
     * @param string      $requestFieldName
     *
     * @return Model
     */
    public function createNewDevice(
        Request $request,
        ?string $code = null,
        bool $verify = false,
        string $requestFieldName = 'deviceId'
    ): Model {
        $request->validate([$requestFieldName => 'required|string|min:3']);
        $data = $this->prepareDataForSave($request->input($requestFieldName));
        $data['code'] = $code;
        $data['verify'] = $verify;

        return $this->create($data);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $requestFieldName
     *
     * @throws \Exception
     */
    public function resendCode(Request $request, string $requestFieldName = 'deviceId'): void
    {
        $request->validate([$requestFieldName => 'required|string|min:3']);
        $device = $this->getDeviceOrFail($request->input($requestFieldName));
        ['code' => $code, 'hash' => $hash] = $this->generateCode();
        $device->update(['code' => $hash]);
        $user = $this->getUser();
        $this->sendNewDeviceEmail(
            $user->email,
            $user->username ?? $user->email,
            $device,
            null,
            $code
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $requestFieldName
     * @param string                   $requestCodeField
     *
     * @return bool
     * @throws ApplicationException
     * @throws BadRequestException
     */
    public function verifyCode(
        Request $request,
        string $requestFieldName = 'deviceId',
        string $requestCodeField = 'code'
    ): bool {
        $request->validate([$requestFieldName => 'required|string|min:3']);
        $device = $this->getDeviceOrFail($request->input($requestFieldName));
        if ($device instanceof DeviceCheckInterface) {
            if ($this->checkCode($request->input($requestCodeField), $device->getCodeHash())) {
                return $device->update(['verify' => true]);
            }

            throw new BadRequestException("Invalid code");
        }
        throw new ApplicationException("Device must be implement DeviceCheckInterface");
    }

    /**
     * @param string $deviceId
     *
     * @return bool
     * @throws ApplicationException
     */
    public function isVerified(string $deviceId): bool
    {
        $device = $this->getDeviceOrFail($deviceId);
        if ($device instanceof DeviceCheckInterface) {
            return $device->isVerified();
        }
        throw new ApplicationException("Device must be implement DeviceCheckInterface");
    }

    /**
     * @param string $deviceId
     *
     * @return Model
     * @throws ApplicationException
     */
    private function getDeviceOrFail(string $deviceId): Model
    {
        if ($this->model instanceof DeviceCheckInterface) {
            return $this->model->ownerDevice($this->getUser()->id ?? null)->where('device_id', $deviceId)
                ->firstOrFail();
        }
        throw new ApplicationException("Base model must be implement DeviceCheckInterface");
    }

    /**
     * @param string $deviceId
     *
     * @return Model
     * @throws ApplicationException
     */
    private function getDevice(string $deviceId): ?Model
    {
        if ($this->model instanceof DeviceCheckInterface) {
            return $this->model->ownerDevice($this->getUser()->id ?? null)->where('device_id', $deviceId)->first();
        }
        throw new ApplicationException("Base model must be implement DeviceCheckInterface");
    }

    /**
     * Send email notification about New Device
     *
     *
     * @param string      $email
     * @param string      $username
     * @param Model       $device
     * @param string|null $ip
     *
     * @param string|null $code
     *
     * @return void
     */
    public function sendNewDeviceEmail(
        string $email,
        string $username,
        Model $device,
        ?string $ip,
        ?string $code = null
    ): void {
        SendNewDeviceEmail::dispatch(
            $email,
            $username,
            $device,
            $ip ? [
                'ip' => $ip,
                'browser' => $this->getBrowserInformation($_SERVER['HTTP_USER_AGENT']),
                'country' => $this->getCountryByIp($ip),
            ] : null,
            $code
        )->onQueue(QueuesNames::DEVICES);
    }

    /**
     * Get Country by ip via ip-api.com api
     *
     * @param string $ip
     *
     * @return string
     */
    public function getCountryByIp(string $ip): string
    {
        $ip = config('app.debug') ? '77.120.241.80' : $ip;

        $ipInfo = json_decode(file_get_contents(sprintf('http://ip-api.com/json/%s', $ip)));

        return $ipInfo->countryCode;
    }

    /**
     * Get Browser Information as Browser Name, Browser Version and Device Name
     *
     * @param string $userAgent
     *
     * @return string
     */
    public function getBrowserInformation(string $userAgent): string
    {
        $browserName = $this->getBrowserName($userAgent);
        $browserVersion = $this->getBrowserVersion($userAgent, $browserName);
        $deviceName = $this->getDeviceName($userAgent);

        return $browserName . ' ' . $browserVersion . ', ' . $deviceName;
    }

    /**
     * Get Browser Name
     *
     * @param string $userAgent
     *
     * @return string
     */
    public function getBrowserName(string $userAgent): ?string
    {
        $browserTypes = [
            'IE' => 'MSIE',
            'Edge' => 'Edge',
            'Opera' => 'OPR',
            'Chromium' => 'Chromium',
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
        ];

        foreach ($browserTypes as $browserType => $browser) {
            if (preg_match('/' . $browser . '/i', $userAgent)) {
                return $browserType;
            }
        }

        return '';
    }

    /**
     * Get Browser Version
     *
     * @param string $userAgent
     * @param string $browserName
     *
     * @return string
     */
    public function getBrowserVersion(string $userAgent, string $browserName = ''): string
    {
        $pattern = '#(?<browser>' . $browserName . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

        preg_match_all($pattern, $userAgent, $matches);

        return $matches['version'][0];
    }

    /**
     * Get Device Name
     *
     * @param string $userAgent
     *
     * @return string
     */
    public function getDeviceName(string $userAgent): ?string
    {
        $devicesTypes = [
            'Windows' => ['Windows'],
            'Linux' => ['X11'],
            'Macintosh' => ['Macintosh'],
            'Mobile' => ['mobile'],
            'Android' => ['android'],
            'iOS' => ['iphone', 'ipad', 'ipod'],
        ];

        foreach ($devicesTypes as $deviceType => $devices) {
            foreach ($devices as $device) {
                if (preg_match('/' . $device . '/i', $userAgent)) {
                    return $deviceType;
                }
            }
        }

        return null;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $requestDeviceField
     *
     * @throws ApplicationException
     * @throws BadRequestException
     */
    public function failIfNotVerified(Request $request, string $requestDeviceField = 'deviceId'): void
    {
        if (!$this->isVerified($request->input($requestDeviceField))) {
            throw new BadRequestException('Device not verified');
        }
    }

    /**
     * @return bool
     * @throws ApplicationException
     */
    protected function hasDevices(): bool
    {
        if ($this->model instanceof DeviceCheckInterface) {
            return $this->model->ownerDevice($this->getUser()->id ?? null)->count() > 0;
        }
        throw new ApplicationException("Base model must be implement DeviceCheckInterface");
    }
}
