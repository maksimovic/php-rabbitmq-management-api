<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Queue;

class QueueTest extends AbstractApiTestCase
{
    private Queue $queue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queue = new Queue($this->client);
    }

    public function testAllWithoutVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues')
            ->willReturn([]);

        $this->assertSame([], $this->queue->all());
    }

    public function testAllWithVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F')
            ->willReturn([]);

        $this->assertSame([], $this->queue->all('/'));
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test')
            ->willReturn([]);

        $this->assertSame([], $this->queue->get('/', 'test'));
    }

    public function testCreate(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test', 'PUT', [], ['durable' => true])
            ->willReturn([]);

        $this->assertSame([], $this->queue->create('/', 'test', ['durable' => true]));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test?', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->queue->delete('/', 'test'));
    }

    public function testDeleteIfEmpty(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test?if-empty=true', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->queue->delete('/', 'test', true));
    }

    public function testDeleteIfUnused(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test?if-unused=true', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->queue->delete('/', 'test', false, true));
    }

    public function testBindings(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test/bindings')
            ->willReturn([]);

        $this->assertSame([], $this->queue->bindings('/', 'test'));
    }

    public function testPurgeMessages(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test/contents', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->queue->purgeMessages('/', 'test'));
    }

    public function testRetrieveMessages(): void
    {
        $expected = ['count' => 5, 'requeue' => true, 'encoding' => 'auto'];

        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test/get', 'POST', [], $expected)
            ->willReturn([]);

        $this->assertSame([], $this->queue->retrieveMessages('/', 'test'));
    }

    public function testRetrieveMessagesWithTruncate(): void
    {
        $expected = ['count' => 1, 'requeue' => false, 'encoding' => 'base64', 'truncate' => 100];

        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/queues/%2F/test/get', 'POST', [], $expected)
            ->willReturn([]);

        $this->assertSame([], $this->queue->retrieveMessages('/', 'test', 1, false, 'base64', 100));
    }
}
