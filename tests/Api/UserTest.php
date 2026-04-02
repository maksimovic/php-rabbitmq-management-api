<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\User;

class UserTest extends AbstractApiTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/users')
            ->willReturn([]);

        $this->assertSame([], $this->user->all());
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/users/guest')
            ->willReturn([]);

        $this->assertSame([], $this->user->get('guest'));
    }

    public function testCreate(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/users/admin', 'PUT', [], ['password' => 'secret', 'tags' => 'administrator'])
            ->willReturn([]);

        $this->assertSame([], $this->user->create('admin', ['password' => 'secret', 'tags' => 'administrator']));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/users/guest', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->user->delete('guest'));
    }

    public function testPermissions(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/users/guest/permissions')
            ->willReturn([]);

        $this->assertSame([], $this->user->permissions('guest'));
    }
}
