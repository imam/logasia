<?php

namespace App\Providers;

use App\Date;
use App\PupTrailer;
use App\SwapBodyTruck;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('dontExceedSwapBodyTruck',function($_,$value, $parameter){
            return $value <= PupTrailer::find($parameter[0])->date->swap_body_truck->vehicles_available;
        });
        \Validator::extend('pupTrailerExceeding',function($_,$value,$parameter){
            return $value >= SwapBodyTruck::find($parameter[0])->date->pup_trailer->vehicles_available;
        });
        \Validator::extend('exceedTotalInventory',function($_,$value,$parameter){
            $model = new $parameter[0];
            return $value > $model->totalInventory;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

    }
}
