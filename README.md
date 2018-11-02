# laravel-sync-broadcaster

### Installation

- Require this package with composer using the following command:

```
composer require lamungu/laravel-sync-broadcaster
```

- After updating composer, add the service provider to the providers array in config/app.php
- Ensure your Laravel Broadcast Service Provider is also enabled
```
'providers' => [
    /*
     * Laravel Framework Service Providers...
     */
     
    // ...
    
    Lamungu\LaravelSyncBroadcaster\SyncBroadcastServiceProvider::class,
    
    // ...
]
```
Laravel 5.5 uses _Package Auto-Discovery_, so it doesn't require you to manually add the ServiceProvider.

- Add the following to your `broadcasting.php` file:

```
    'connections' => [
    
        // ...
        
        'sync' => [
            'driver' => 'sync',
            'accountSid' => env('TWILIO_ACCOUNT_SID'),
            'authToken' => env('TWILIO_AUTH_TOKEN'),
            'serviceSid' => env('TWILIO_SYNC_SERVICE_SID'),
            'key' => env('TWILIO_SYNC_KEY'),
            'secret' => env('TWILIO_SYNC_SECRET')
        ]
        
        // ...
    ]
```

- Finally add the following Environment Variables to your `.env` file

```
# Enable Twilio Sync as a Broadcasting Driver
BROADCAST_DRIVER=sync

# Replace the values for the ones found in your twilio account
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_SYNC_SERVICE_SID=SKxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_SYNC_API_KEY=SKxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_SYNC_API_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```
