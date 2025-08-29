<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | Configure your CORS settings here. By default, all of the values are
    | set to allow for all origins and methods.
    |
    */

    'paths' => ['api/*', 'scan'], // Atur untuk rute API

    'allowed_methods' => ['*'], // Mengizinkan semua metode HTTP (GET, POST, PUT, DELETE, dll.)

    'allowed_origins' => ['*'], // Mengizinkan semua asal (origin). Sesuaikan dengan domain yang Anda perlukan.

    'allowed_headers' => ['*'], // Mengizinkan semua header

    'exposed_headers' => ['*'], // Jika perlu mengekspos header khusus ke browser

    'max_age' => 0, // Mengatur waktu cache pre-flight request

    'supports_credentials' => false, // Jika perlu mendukung kredensial (cookies, authorization headers)
];
