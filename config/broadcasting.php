<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'pusher'),


    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher' => [
              'driver' => 'pusher',
              'key' => env('PUSHER_APP_KEY'),
              'secret' => env('PUSHER_APP_SECRET'),
              'app_id' => env('PUSHER_APP_ID'),
              'options' => [
                  'cluster' => env('PUSHER_APP_CLUSTER'),
                  'useTLS' => true,   // Set this based on whether you're using HTTPS or not
                  'encrypted' => true,  // This ensures you use encrypted (TLS/SSL) connections
                  'host' => env('PUSHER_HOST'),  // You can set this to your WebSocket server domain or IP
                  'port' => env('PUSHER_PORT'),  // Set this to the correct WebSocket port, e.g., 443 if you're using it
                  'scheme' => env('PUSHER_SCHEME'),  // Set to 'https' for secure WebSocket (WSS)
                  'curl_options' => [
                      CURLOPT_SSL_VERIFYHOST => 0,  // Optional: Set to 0 if having SSL verification issues
                      CURLOPT_SSL_VERIFYPEER => 0,  // Optional: Set to 0 if having SSL verification issues
                  ],
              ],
          ],




        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
