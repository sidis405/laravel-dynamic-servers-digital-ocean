<?php

namespace Sidis405\LaravelDynamicServersDigitalOcean\DigitalOcean;

class DigitalOceanServer
{
    public function __construct(
        public string $uuid,
        public string $title,
        public string $ip,
        public DigitalOceanServerStatus $status,
    ) {
    }

    public static function fromApiPayload(array $payload): self
    {
        $ip = collect($payload['networks']['v4'])
            ->where('type', 'public')
            ->first()['ip_address'] ?? '';

        return new self(
            $payload['id'],
            $payload['name'],
            $ip,
            DigitalOceanServerStatus::from($payload['status']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'ip' => $this->ip,
            'status' => $this->status->value,
        ];
    }
}
