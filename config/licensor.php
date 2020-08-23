<?php

return [

    /*
     * The path of the licensee's URL to which the private key will be sent
     * during key activation and verification
     */
    'licensee_callback_path' => 'key/callback',

    /*
     * Limitation of requests from a licensee.
     * See \Illuminate\Routing\Middleware\ThrottleRequests::class
     */
    'request_throttle' => '3',

    /*
     * Paths to activate and verify licensees' keys on this server
     */
    'key_verification_path' => 'key/check',
    'key_activation_path' => 'key/activate',

    /*
     * Private key settings
     */
    'key_expiration_time_offset' => 60 * 60 * 24,
    'key_shutdown_time_offset' => 60 * 60 * 24,
];
