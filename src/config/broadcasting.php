<?php

return [
    'connections' => [
        'sync' => [
            'driver' => 'sync',
            'accountSid' => env('TWILIO_ACCOUNT_SID'),
            'authToken' => env('TWILIO_AUTH_TOKEN'),
            'serviceSid' => env('TWILIO_SYNC_SERVICE_SID'),
            'key' => env('TWILIO_SYNC_KEY'),
            'secret' => env('TWILIO_SYNC_SECRET')
        ]
    ]
];
