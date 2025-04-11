<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class TwilioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('twilio', function ($app) {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $twilioNumber = config('services.twilio.phone_number');

            return new Client($sid, $token);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
