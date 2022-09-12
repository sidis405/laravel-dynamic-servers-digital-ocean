<?php

namespace Sidis405\LaravelDynamicServersDigitalOcean\DigitalOcean\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Spatie\DynamicServers\Models\Server;

class CannotGetDigitalOceanServerDetails extends Exception
{
    public static function make(
        Server $server,
        Response $response
    ): self {
        $reason = $response->json('error.error_message');

        return new self("Could not refresh details for DigitalOcean server id {$server->id}: $reason");
    }
}
