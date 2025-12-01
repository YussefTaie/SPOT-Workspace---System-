<?php

return [


    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],



    'guards' => [
    'admin' => ['driver' => 'session', 'provider' => 'staffs'],
    'barista' => ['driver' => 'session', 'provider' => 'staffs'],
    'host' => ['driver' => 'session', 'provider' => 'staffs'],
],
'providers' => [
    'staffs' => ['driver' => 'eloquent', 'model' => App\Models\Staff::class],
],








    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],



    'password_timeout' => 10800,

];
