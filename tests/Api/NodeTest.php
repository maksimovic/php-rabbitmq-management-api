<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Node;

class NodeTest extends AbstractApiTestCase
{
    private Node $node;

    protected function setUp(): void
    {
        parent::setUp();
        $this->node = new Node($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/nodes')
            ->willReturn([]);

        $this->assertSame([], $this->node->all());
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/nodes/rabbit%40localhost')
            ->willReturn([]);

        $this->assertSame([], $this->node->get('rabbit@localhost'));
    }

    public function testGetWithMemory(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/nodes/rabbit%40localhost?memory=true')
            ->willReturn([]);

        $this->assertSame([], $this->node->get('rabbit@localhost', true));
    }
}
