<?php

namespace Sidis405\LaravelDynamicServersDigitalOcean\DigitalOcean;

enum DigitalOceanServerStatus: string
{
    case New = 'new';
    case Active = 'active';
    case Off = 'off';
    case Archive = 'archive';
}
