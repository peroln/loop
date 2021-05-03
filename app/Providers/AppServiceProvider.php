<?php

namespace App\Providers;

use App\Http\Controllers\User\AuthController;
//use App\Models\Helpers\CryptoServiceInterface;
use App\Repositories\Base\RepositoryInterface;
use App\Repositories\UserRepository;
//use App\Services\TronService;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
   /* public array $bindings = [
        CryptoServiceInterface::class => TronService::class,
    ];*/
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(AuthController::class)
            ->needs(RepositoryInterface::class)
            ->give(UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.use_https')) {
            URL::forceScheme('https');
        }
    }
}
