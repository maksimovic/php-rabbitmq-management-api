<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Api;

use RabbitMq\ManagementApi\Client;

/**
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class AbstractApi
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
