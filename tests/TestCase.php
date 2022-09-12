<?php

namespace Sidis405\LaravelDynamicServersDigitalOcean\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sidis405\LaravelDynamicServersDigitalOcean\LaravelDynamicServersDigitalOceanServiceProvider;
use Sidis405\LaravelDynamicServersDigitalOcean\Tests\TestSupport\ServerProviders\DummyServerProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDigitalOceanTestProvider();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDynamicServersDigitalOceanServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    protected function setUpDigitalOceanTestProvider(): self
    {
        $this->setDefaultServerProvider(DummyServerProvider::class);

        $providerConfig = config('dynamic-servers.providers');
        $providerConfig['other_provider'] = [
            'class' => DummyServerProvider::class,
        ];

        config()->set('dynamic-servers.providers', $providerConfig);

        return $this;
    }

    protected function setDefaultServerProvider(string $serverProvider): self
    {
        config()->set('dynamic-servers.providers.digital_ocean.class', $serverProvider);

        return $this;
    }

    public function digitalOceanHasBeenConfigured(): bool
    {
        return config('dynamic-servers.providers.digital_ocean.options.token') !== null;
    }
}
