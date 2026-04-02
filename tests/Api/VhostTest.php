<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Vhost;

class VhostTest extends AbstractApiTestCase
{
    private Vhost $vhost;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vhost = new Vhost($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/vhosts')
            ->willReturn([]);

        $this->assertSame([], $this->vhost->all());
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/vhosts/%2F')
            ->willReturn([]);

        $this->assertSame([], $this->vhost->get('/'));
    }

    public function testCreate(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/vhosts/test', 'PUT')
            ->willReturn([]);

        $this->assertSame([], $this->vhost->create('test'));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/vhosts/test', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->vhost->delete('test'));
    }

    public function testPermissions(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/vhosts/%2F/permissions')
            ->willReturn([]);

        $this->assertSame([], $this->vhost->permissions('/'));
    }
}
