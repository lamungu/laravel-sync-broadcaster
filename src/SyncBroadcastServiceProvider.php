<?php
namespace Lamungu\LaravelSyncBroadcaster;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Lamungu\LaravelSyncBroadcaster\Broadcasters\SyncBroadcaster;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class SyncBroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::extend('sync',function ($app) {
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
        $this->app->singleton('sync', function ($app) {
            $client = new TwilioClient($app['config']->get('broadcasting.connections.sync.accountSid'), $app['config']->get('broadcasting.connections.sync.authToken'));
            return $client->sync->services($app['config']->get('broadcasting.connections.sync.serviceSid'));
        });
    }
}
