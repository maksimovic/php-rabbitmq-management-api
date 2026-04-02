<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Connection;

class ConnectionTest extends AbstractApiTestCase
{
    private Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = new Connection($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/connections')
            ->willReturn([]);

        $this->assertSame([], $this->connection->all());
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/connections/conn1')
            ->willReturn([]);

        $this->assertSame([], $this->connection->get('conn1'));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/connections/conn1', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->connection->delete('conn1'));
    }
}
