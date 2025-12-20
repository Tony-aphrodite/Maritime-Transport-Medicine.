<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailtrapApiTransport;

class MailtrapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Mail::extend('mailtrap', function (array $config = []) {
            $apiToken = $config['api_token'] ?? config('services.mailtrap.token');
            return new MailtrapApiTransport($apiToken);
        });
    }
}
