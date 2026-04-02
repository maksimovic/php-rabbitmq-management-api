<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Parameter;

class ParameterTest extends AbstractApiTestCase
{
    private Parameter $parameter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parameter = new Parameter($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/parameters')
            ->willReturn([]);

        $this->assertSame([], $this->parameter->all());
    }

    public function testGetByComponent(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/parameters/federation')
            ->willReturn([]);

        $this->assertSame([], $this->parameter->get('federation'));
    }

    public function testGetByComponentAndVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/parameters/federation/%2F')
            ->willReturn([]);

        $this->assertSame([], $this->parameter->get('federation', '/'));
    }

    public function testGetByComponentVhostAndName(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/parameters/federation/%2F/my-param')
            ->willReturn([]);

        $this->assertSame([], $this->parameter->get('federation', '/', 'my-param'));
    }

    public function testCreate(): void
    {
        $params = ['value' => ['uri' => 'amqp://remote']];

        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/parameters/federation/%2F/my-param', 'PUT', [], $params)
            ->willReturn([]);

        $this->assertSame([], $this->parameter->create('federation', '/', 'my-param', $params));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/parameters/federation/%2F/my-param', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->parameter->delete('federation', '/', 'my-param'));
    }
}
