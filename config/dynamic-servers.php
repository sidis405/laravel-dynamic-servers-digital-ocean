<?php

return [
    'providers' => [
        'digital_ocean' => [
            'class' => Sidis405\LaravelDynamicServersDigitalOcean\DigitalOcean\DigitalOceanServerProvider::class,
            'maximum_servers_in_account' => 20,
            'options' => [
                'token' => env('DIGITAL_OCEAN_TOKEN'),
                'region' => env('DIGITAL_OCEAN_REGION'),
                'size' => env('DIGITAL_OCEAN_SIZE'),
                'image' => env('DIGITAL_OCEAN_IMAGE'),
                'vpc_uuid' => env('DIGITAL_OCEAN_VPC_UUID'),
            ],
        ],
    ],
];
