<?php

namespace App\Providers;

use App\Providers\SalesforceProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class SalesforceSocialiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Socialite::extend('salesforce', function ($app) {
            $config = $app['config']['services.salesforce'];
            return Socialite::buildProvider(SalesforceProvider::class, $config);
        });
    }

    public function register()
    {
        //
    }
}
