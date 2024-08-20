<?php

namespace App\Providers;

use App\Repositories\Contracts\ICityRepository;
use App\Repositories\Contracts\IVehicleTypeRepository;
use App\Repositories\Eloquent\CityRepository;
use App\Repositories\Eloquent\VehicleTypeRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ICityRepository::class, CityRepository::class);
        $this->app->bind(IVehicleTypeRepository::class, VehicleTypeRepository::class);
    }

    /**
     * Bootstrap any application services.
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        app()->make('router')->matched(function ($event) {
            $request = $event->route->getAction()['request'] ?? app(Request::class);

            if ($request->is('api/*')) {
                $request->headers->set('Accept', 'application/json');
            }
        });
    }
}
