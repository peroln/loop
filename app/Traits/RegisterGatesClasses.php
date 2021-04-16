<?php

namespace App\Traits;

use App\Gates\CarrierAccessGate;
use App\Gates\ChatAccessGate;
use App\Gates\DisputeAccessGate;
use App\Policy\PolicyInterface;
use App\Gates\ReviewAccessGate;
use App\Gates\ShipperAccessGate;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait RegisterGatesClasses
 *
 * @package App\Traits
 */
trait RegisterGatesClasses
{
    /**
     * Register gates
     *
     * @return array
     */
    protected function gates(): array
    {
        return [
            PolicyInterface::STORE_SHIPPER_REVIEW       => ReviewAccessGate::class . '@storeShipperReview',
            PolicyInterface::STORE_CARRIER_REVIEW       => ReviewAccessGate::class . '@storeCarrierReview',
            PolicyInterface::IS_CREATOR_DISPUTE         => DisputeAccessGate::class . '@isCreatorDispute',
            PolicyInterface::EXISTS_DISPUTE             => DisputeAccessGate::class . '@existsDispute',
            PolicyInterface::SIPPER_EXISTS_LOAD         => ShipperAccessGate::class . '@existsLoad',
            PolicyInterface::CARRIER_EXISTS_TRUCK       => CarrierAccessGate::class . '@existsTruck',
            PolicyInterface::CORRECT_LOAD_AND_TRUCK     => ChatAccessGate::class . '@correctLoadAndTruck',
            PolicyInterface::CHECK_CREATE_CHAT          => ChatAccessGate::class . '@checkCreateChat',
            PolicyInterface::CHECK_LOAD_BINDING_TRUCK   => ShipperAccessGate::class . '@checkLoadBindingTruck',
            PolicyInterface::CHECK_TRUCK_BINDING_LOAD   => CarrierAccessGate::class . '@checkTruckBindingLoad',
        ];
    }

    /**
     * Register gates classes
     */
    protected function registerGates(): void
    {
        Gate::before(fn(Model $user): ?bool => ($user instanceof Admin) == false ? null : true);

        foreach ($this->gates() as $name => $cateCallable) {
            Gate::define($name, $cateCallable);
        }
    }
}
