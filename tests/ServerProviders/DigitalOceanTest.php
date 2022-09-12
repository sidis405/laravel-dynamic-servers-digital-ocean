<?php

use Illuminate\Support\Str;
use Sidis405\LaravelDynamicServersDigitalOcean\DigitalOcean\DigitalOceanServerProvider;
use Spatie\DynamicServers\Models\Server;

beforeEach(function () {
    if (! $this->digitalOceanHasBeenConfigured()) {
        $this->markTestSkipped('Digital Ocean not configured');
    }

    $server = Server::factory()->create()->addMeta('server_properties.uuid', Str::uuid());

    $this->digitalOceanServerProvider = (new DigitalOceanServerProvider())->setServer($server);
});

it('can determine the total number of servers on DigitalOcean', function () {
    expect($this->digitalOceanServerProvider->currentServerCount())->toBeInt();
});

it('can determine that the server has been deleted', function () {
    expect($this->digitalOceanServerProvider->hasBeenDeleted())->toBeTrue();
});
