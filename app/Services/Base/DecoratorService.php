<?php

namespace App\Services\Base;

use App\Exceptions\Application\ApplicationException;

abstract class DecoratorService
{
    /**
     * @var \App\Services\Base\BaseModelService
     */
    protected $decoratedService;

    /**
     * DecoratorService constructor.
     *
     * @param $decoratedService
     */
    public function __construct(BaseModelService $decoratedService)
    {
        $this->decoratedService = $decoratedService;
    }

    /**
     * Call decorated class method
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws ApplicationException
     */
    private function makeCall(string $method, array $arguments)
    {
        $instance = $this->decoratedService;
        if (!$instance) {
            throw new ApplicationException('A decorator class has not been set.');
        }

        return $instance->$method(...$arguments);
    }

    /**
     * Handle calling class method
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws ApplicationException
     */
    public function __call($name, $arguments)
    {
        return $this->makeCall($name, $arguments);
    }
}
