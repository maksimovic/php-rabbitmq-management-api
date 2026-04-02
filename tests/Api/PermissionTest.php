<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Permission;

class PermissionTest extends AbstractApiTestCase
{
    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();
        $this->permission = new Permission($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/permissions')
            ->willReturn([]);

        $this->assertSame([], $this->permission->all());
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/permissions/%2F/guest')
            ->willReturn([]);

        $this->assertSame([], $this->permission->get('/', 'guest'));
    }

    public function testCreate(): void
    {
        $perms = ['configure' => '.*', 'write' => '.*', 'read' => '.*'];

        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/permissions/%2F/guest', 'PUT', [], $perms)
            ->willReturn([]);

        $this->assertSame([], $this->permission->create('/', 'guest', $perms));
    }

    public function testCreateThrowsWithMissingKeys(): void
    {
        $this->expectException(\RabbitMq\ManagementApi\Exception\InvalidArgumentException::class);

        $this->permission->create('/', 'guest', ['configure' => '.*']);
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/permissions/%2F/guest', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->permission->delete('/', 'guest'));
    }
}
