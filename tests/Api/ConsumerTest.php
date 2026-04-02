<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Consumer;

class ConsumerTest extends AbstractApiTestCase
{
    private Consumer $consumer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->consumer = new Consumer($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/consumers')
            ->willReturn([]);

        $this->assertSame([], $this->consumer->all());
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/consumers/%2F')
            ->willReturn([]);

        $this->assertSame([], $this->consumer->get('/'));
    }
}
