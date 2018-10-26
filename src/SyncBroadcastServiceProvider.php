<?php
namespace Lamungu\LaravelSyncBroadcaster;

use Lamungu\LaravelSyncBroadcaster\Broadcasters\SyncBroadcaster;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class SyncBroadcastServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::extend('sync', function ($app) {
            return new SyncBroadcaster($app->make('sync'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/broadcasting.php', 'broadcasting'
        );

        $this->app->singleton('sync', function ($app) {
            $client = new TwilioClient($app['config']->get('broadcasting.connection.sync.accountSid'), $app['config']->get('broadcasting.connection.sync.authToken'));
            return $client->sync->services($app['config']->get('broadcasting.connection.sync.serviceSid'));
        });
    }
}
