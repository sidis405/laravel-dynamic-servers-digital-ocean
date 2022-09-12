<?php

namespace Sidis405\LaravelDynamicServersDigitalOcean\DigitalOcean;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Spatie\DynamicServers\ServerProviders\DigitalOcean\Exceptions\CannotGetDigitalOceanServerDetails;
use Spatie\DynamicServers\ServerProviders\DigitalOcean\Exceptions\CannotRebootServer;
use Spatie\DynamicServers\ServerProviders\ServerProvider;

class DigitalOceanServerProvider extends ServerProvider
{
    public function createServer(): void
    {
        $response = $this->request()->post('/droplets', $this->server->configuration);

        if (! $response->successful()) {
            throw new Exception($response->json('message'));
        }

        $digitalOceanServer = DigitalOceanServer::fromApiPayload($response->json('droplet'));

        $this->server->addMeta('server_properties', $digitalOceanServer->toArray());
    }

    public function hasStarted(): bool
    {
        $digitalOceanServer = $this->getServer();

        return $digitalOceanServer->status === DigitalOceanServerStatus::Active;
    }

    public function stopServer(): void
    {
        $serverId = $this->server->meta('server_properties.id');

        $response = $this->request()->post("/droplets/{$serverId}/actions", [
            'stop_server' => [
                'type' => 'power_off',
            ],
        ]);

        if (! $response->successful()) {
            throw new Exception($response->json('error.error_message'));
        }
    }

    public function hasStopped(): bool
    {
        $digitalOceanServer = $this->getServer();

        return $digitalOceanServer->status === DigitalOceanServerStatus::Off;
    }

    public function deleteServer(): void
    {
        $serverId = $this->server->meta('server_properties.id');

        $response = $this->request()->delete("/droplets/{$serverId}");

        if (! $response->successful()) {
            throw new Exception($response->json('error.error_message', 'Could not delete server'));
        }
    }

    public function hasBeenDeleted(): bool
    {
        $serverId = $this->server->meta('server_properties.id');

        $response = $this->request()->get("/droplets/{$serverId}");

        return $response->failed();
    }

    public function getServer(): DigitalOceanServer
    {
        $serverId = $this->server->meta('server_properties.id');

        $response = $this->request()->get("/droplets/{$serverId}");

        if (! $response->successful()) {
            throw CannotGetDigitalOceanServerDetails::make($this->server, $response);
        }

        return DigitalOceanServer::fromApiPayload($response->json('droplet'));
    }

    public function rebootServer(): void
    {
        $serverId = $this->server->meta('server_properties.id');

        $response = $this->request()->post("/droplets/{$serverId}/actions", [
            'type' => 'reboot',
        ]);

        if (! $response->successful()) {
            throw CannotRebootServer::make($this->server, $response);
        }
    }

    public function currentServerCount(): int
    {
        $response = $this->request()->get('droplets');

        if (! $response->successful()) {
            throw CannotGetDigitalOceanServerDetails::make($this->server, $response);
        }

        return count($response->json('droplets'));
    }

    protected function request(): PendingRequest
    {
        return Http::withToken(
            $this->server->option('token')
        )->baseUrl('https://api.digitalocean.com/v2');
    }
}
