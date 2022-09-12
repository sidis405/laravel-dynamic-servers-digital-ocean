# Digital Ocean provider for Spatie's Dynamic Servers Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sidis405/laravel-dynamic-servers-digital-ocean.svg?style=flat-square)](https://packagist.org/packages/sidis405/laravel-dynamic-servers-digital-ocean)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sidis405/laravel-dynamic-servers-digital-ocean/run-tests?label=tests)](https://github.com/sidis405/laravel-dynamic-servers-digital-ocean/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/sidis405/laravel-dynamic-servers-digital-ocean/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/sidis405/laravel-dynamic-servers-digital-ocean/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sidis405/laravel-dynamic-servers-digital-ocean.svg?style=flat-square)](https://packagist.org/packages/sidis405/laravel-dynamic-servers-digital-ocean)

This package provides a <a href="https://digitalocean.com"> Server Provider for Spatie's <a href="https://github.com/spatie/laravel-dynamic-servers">Laravel Dynamic Servers</a> Package.

## Installation

You can install the package via composer:

```bash
composer require sidis405/laravel-dynamic-servers-digital-ocean
```

Afterward make sure to publish the EventServiceProvider that comes with this package:

```bash
php artisan dynamic-servers-digital-ocean:install
```

## Usage

In your config/dynamic-servers.php register the DO provider
```php
return [
    'providers' => [
        ...

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
    ]
];
```

In your app/Providers/DynamicServersProvider.php register a new server type using the Digital Ocean provider
```php
public function register()
{
    ....

    $doServer = ServerType::new('do')
        ->provider('digital_ocean')
        ->configuration(function(Server $server) {
            return [
                'name' => Str::slug($server->name),

                "image" => $server->option('image'),
                "vpc_uuid" => $server->option('vpc_uuid'),
                "region" => $server->option('region'),
                "size" => $server->option('size'),

                "ipv6" => false,
                "backups" => false,
                "monitoring" => true,
            ];
        });

        DynamicServers::registerServerType($doServer);
}
```

## Events
After the base package's `CreateServerJob` is executed, a new job, `VerifyServerStartedJob` will be dispatched and will check every 20 seconds to make sure that the provider eventually marks the Droplet as running.

After it ensures it runs, no other attempt is made to fetch again the server meta.

Considering that DigitalOcean will return the ip address of a droplet only after it has been full created we need to fetch once more the droplet meta.

For this, we will use the base package's event 'ServerRunningEvent'.

This package, publishes a `App\Providers\DigitalOceanEventServiceProvider` in your project.

By default there is a single listener, configured and it will fetch again the Droplet's meta after the base package has ensured that it is running.

```php
protected $listen = [
        ServerRunningEvent::class => [
            UpdateServerMeta::class,
        ],
    ];
```

You may customise the listener, disable it or replace it with a your own.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sidrit Trandafili](https://github.com/sidis405)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
