<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RabbitMq\ManagementApi\Client;

abstract class AbstractApiTestCase extends TestCase
{
    protected Client|MockObject $client;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
    }
}
