<?php

namespace App\Traits;

use App\Providers\AppServiceProviderInterface;
use Illuminate\Contracts\Foundation\Application;

/**
 * Trait RegisterServicesClasses
 *
 * @package App\Traits
 */
trait RegisterServicesClasses
{
    /**
     * Register services
     *
     * @return array
     */
    protected function services(): array
    {
        return [
            // Example
            //AdminService::class => [self::SERVICE_BIND, fn() => new AdminService($this->app)],
        ];
    }

    /**
     * Array of services which must be extended
     *
     * @return array
     */
    protected function extendServices(): array
    {
        return [
            // Example
            //[Service::class, fn($service) => new DecoratorService($service)],
        ];
    }

    /**
     * Register gates classes
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function registerServices(Application &$app): void
    {
        $this->mapServices($this->services(), $app);
        $this->mapExtendsServices($this->extendServices(), $app);
    }

    /**
     * Register services defined in arrays
     *
     * @param array $services
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    private function mapServices(array $services, Application &$app): void
    {

        foreach ($services as $serviceName => $serviceInitArgs) {
            if (!is_array($serviceInitArgs) || count($serviceInitArgs) > 3 || count($serviceInitArgs) < 2) {
                continue;
            }
            if (count($serviceInitArgs) == 3) {
                [$type, $callable, $skip] = $serviceInitArgs;
                if (is_bool($skip) && $skip) {
                    continue;
                }
                $this->registerCallable($app, $type, $serviceName, $callable);
            } else {
                if (count($serviceInitArgs) == 2) {
                    [$type, $callable] = $serviceInitArgs;
                    $this->registerCallable($app, $type, $serviceName, $callable);
                }
            }
        }
    }

    /**
     * Register extended services
     *
     * @param array $services
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    private function mapExtendsServices(array $services, Application &$app): void
    {
        foreach ($services as $decorateService) {
            if (!is_array($decorateService) || count($decorateService) > 2 || count($decorateService) < 2) {
                continue;
            }
            [$baseService, $callable] = $decorateService;
            $this->registerCallable($app, AppServiceProviderInterface::SERVICE_EXTEND, $baseService, $callable);
        }
    }

    /**
     * Register service in service container
     *
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param string $type
     * @param string $serviceName
     * @param callable|null $callable
     */
    private function registerCallable(
        Application $application,
        string $type,
        string $serviceName,
        ?callable $callable
    ): void {
        if (is_null($callable)) {
            $application->$type($serviceName);
        } else {
            $application->$type($serviceName, $callable);
        }
    }
}
