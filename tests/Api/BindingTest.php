<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Binding;

class BindingTest extends AbstractApiTestCase
{
    private Binding $binding;

    protected function setUp(): void
    {
        parent::setUp();
        $this->binding = new Binding($this->client);
    }

    public function testAllWithoutVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings')
            ->willReturn([]);

        $this->assertSame([], $this->binding->all());
    }

    public function testAllWithVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings/%2F')
            ->willReturn([]);

        $this->assertSame([], $this->binding->all('/'));
    }

    public function testBinding(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings/%2F/e/ex/q/queue')
            ->willReturn([]);

        $this->assertSame([], $this->binding->binding('/', 'ex', 'queue'));
    }

    public function testExchangeBinding(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings/%2F/e/src/e/dest')
            ->willReturn([]);

        $this->assertSame([], $this->binding->exchangeBinding('/', 'src', 'dest'));
    }

    public function testCreate(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with(
                '/api/bindings/%2F/e/ex/q/queue',
                'POST',
                [],
                ['routing_key' => 'rk', 'arguments' => ['x-match' => 'all']]
            )
            ->willReturn([]);

        $this->assertSame([], $this->binding->create('/', 'ex', 'queue', 'rk', ['x-match' => 'all']));
    }

    public function testCreateWithDefaults(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with(
                '/api/bindings/%2F/e/ex/q/queue',
                'POST',
                [],
                ['routing_key' => '']
            )
            ->willReturn([]);

        $this->assertSame([], $this->binding->create('/', 'ex', 'queue'));
    }

    public function testCreateWithNullArguments(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with(
                '/api/bindings/%2F/e/ex/q/queue',
                'POST',
                [],
                ['routing_key' => 'rk']
            )
            ->willReturn([]);

        $this->assertSame([], $this->binding->create('/', 'ex', 'queue', 'rk', null));
    }

    public function testCreateExchange(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with(
                '/api/bindings/%2F/e/src/e/dest',
                'POST',
                [],
                ['routing_key' => 'rk', 'arguments' => ['x-match' => 'all']]
            )
            ->willReturn([]);

        $this->assertSame([], $this->binding->createExchange('/', 'src', 'dest', 'rk', ['x-match' => 'all']));
    }

    public function testCreateExchangeWithDefaults(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with(
                '/api/bindings/%2F/e/src/e/dest',
                'POST',
                [],
                ['routing_key' => '']
            )
            ->willReturn([]);

        $this->assertSame([], $this->binding->createExchange('/', 'src', 'dest'));
    }

    public function testCreateExchangeWithNullArguments(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with(
                '/api/bindings/%2F/e/src/e/dest',
                'POST',
                [],
                ['routing_key' => 'rk']
            )
            ->willReturn([]);

        $this->assertSame([], $this->binding->createExchange('/', 'src', 'dest', 'rk', null));
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings/%2F/e/ex/q/queue/props')
            ->willReturn([]);

        $this->assertSame([], $this->binding->get('/', 'ex', 'queue', 'props'));
    }

    public function testGetExchange(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings/%2F/e/src/e/dest/props')
            ->willReturn([]);

        $this->assertSame([], $this->binding->getExchange('/', 'src', 'dest', 'props'));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings/%2F/e/ex/q/queue/props', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->binding->delete('/', 'ex', 'queue', 'props'));
    }

    public function testDeleteExchange(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/bindings/%2F/e/src/e/dest/props', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->binding->deleteExchange('/', 'src', 'dest', 'props'));
    }
}
