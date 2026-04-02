<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Exchange;

class ExchangeTest extends AbstractApiTestCase
{
    private Exchange $exchange;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exchange = new Exchange($this->client);
    }

    public function testAllWithoutVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges')
            ->willReturn([]);

        $this->assertSame([], $this->exchange->all());
    }

    public function testAllWithVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges/%2F')
            ->willReturn([]);

        $this->assertSame([], $this->exchange->all('/'));
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges/%2F/amq.direct')
            ->willReturn([]);

        $this->assertSame([], $this->exchange->get('/', 'amq.direct'));
    }

    public function testCreate(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges/%2F/test', 'PUT', [], ['type' => 'direct'])
            ->willReturn([]);

        $this->assertSame([], $this->exchange->create('/', 'test', ['type' => 'direct']));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges/%2F/test', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->exchange->delete('/', 'test'));
    }

    public function testSourceBindings(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges/%2F/amq.direct/bindings/source')
            ->willReturn([]);

        $this->assertSame([], $this->exchange->sourceBindings('/', 'amq.direct'));
    }

    public function testDestinationBindings(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges/%2F/amq.direct/bindings/destination')
            ->willReturn([]);

        $this->assertSame([], $this->exchange->destinationBindings('/', 'amq.direct'));
    }

    public function testPublish(): void
    {
        $message = ['properties' => [], 'routing_key' => 'rk', 'payload' => 'test', 'payload_encoding' => 'string'];

        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/exchanges/%2F/amq.direct/publish', 'POST', [], $message)
            ->willReturn(['routed' => true]);

        $this->assertSame(['routed' => true], $this->exchange->publish('/', 'amq.direct', $message));
    }

    public function testCreateThrowsWithoutType(): void
    {
        $this->expectException(\RabbitMq\ManagementApi\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage("Exchange key 'type' is mandatory");

        $this->exchange->create('/', 'test', ['durable' => true]);
    }

    public function testPublishThrowsWithoutProperties(): void
    {
        $this->expectException(\RabbitMq\ManagementApi\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage("Message key 'properties' is mandatory");

        $this->exchange->publish('/', 'amq.direct', ['routing_key' => 'rk', 'payload' => 'test', 'payload_encoding' => 'string']);
    }

    public function testPublishThrowsWithoutRoutingKey(): void
    {
        $this->expectException(\RabbitMq\ManagementApi\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage("Message key 'routing_key' is mandatory");

        $this->exchange->publish('/', 'amq.direct', ['properties' => []]);
    }

    public function testPublishThrowsWithoutPayload(): void
    {
        $this->expectException(\RabbitMq\ManagementApi\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage("Message key 'payload' is mandatory");

        $this->exchange->publish('/', 'amq.direct', ['properties' => [], 'routing_key' => 'rk']);
    }

    public function testPublishThrowsWithoutPayloadEncoding(): void
    {
        $this->expectException(\RabbitMq\ManagementApi\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage("Message key 'payload_encoding' is mandatory");

        $this->exchange->publish('/', 'amq.direct', ['properties' => [], 'routing_key' => 'rk', 'payload' => 'test']);
    }
}
