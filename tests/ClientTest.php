<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use RabbitMq\ManagementApi\Api;
use RabbitMq\ManagementApi\Client;

class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['send'])
            ->getMock();
    }

    public function testConnections(): void
    {
        $this->assertInstanceOf(Api\Connection::class, $this->client->connections());
    }

    public function testChannels(): void
    {
        $this->assertInstanceOf(Api\Channel::class, $this->client->channels());
    }

    public function testConsumers(): void
    {
        $this->assertInstanceOf(Api\Consumer::class, $this->client->consumers());
    }

    public function testExchanges(): void
    {
        $this->assertInstanceOf(Api\Exchange::class, $this->client->exchanges());
    }

    public function testQueues(): void
    {
        $this->assertInstanceOf(Api\Queue::class, $this->client->queues());
    }

    public function testVhosts(): void
    {
        $this->assertInstanceOf(Api\Vhost::class, $this->client->vhosts());
    }

    public function testBindings(): void
    {
        $this->assertInstanceOf(Api\Binding::class, $this->client->bindings());
    }

    public function testUsers(): void
    {
        $this->assertInstanceOf(Api\User::class, $this->client->users());
    }

    public function testPermissions(): void
    {
        $this->assertInstanceOf(Api\Permission::class, $this->client->permissions());
    }

    public function testParameters(): void
    {
        $this->assertInstanceOf(Api\Parameter::class, $this->client->parameters());
    }

    public function testPolicies(): void
    {
        $this->assertInstanceOf(Api\Policy::class, $this->client->policies());
    }

    public function testAlivenessTest(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/aliveness-test/%2F')
            ->willReturn(['status' => 'ok']);

        $this->assertSame(['status' => 'ok'], $this->client->alivenessTest('/'));
    }

    public function testOverview(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/overview')
            ->willReturn([]);

        $this->assertSame([], $this->client->overview());
    }

    public function testExtensions(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/extensions')
            ->willReturn([]);

        $this->assertSame([], $this->client->extensions());
    }

    public function testDefinitions(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/definitions')
            ->willReturn([]);

        $this->assertSame([], $this->client->definitions());
    }

    public function testWhoami(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/whoami')
            ->willReturn(['name' => 'guest']);

        $this->assertSame(['name' => 'guest'], $this->client->whoami());
    }

    public function testConstructorWithMockedHttpClient(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $client = new Client($httpClient, 'http://rabbit:15672', 'admin', 'secret');

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testConstructorWithDefaultClient(): void
    {
        $client = new Client(null, 'http://rabbit:15672');

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testSendGetRequest(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getMethod() === 'GET'
                    && (string)$request->getUri() === 'http://rabbit:15672/api/overview';
            }))
            ->willReturn(new Response(200, [], '{"cluster_name":"rabbit@localhost"}'));

        $client = new Client($httpClient, 'http://rabbit:15672');
        $result = $client->send('/api/overview');

        $this->assertSame(['cluster_name' => 'rabbit@localhost'], $result);
    }

    public function testSendPostRequestWithBody(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getMethod() === 'POST'
                    && (string)$request->getUri() === 'http://rabbit:15672/api/exchanges/%2F/test/publish';
            }))
            ->willReturn(new Response(200, [], '{"routed":true}'));

        $client = new Client($httpClient, 'http://rabbit:15672');
        $result = $client->send('/api/exchanges/%2F/test/publish', 'POST', [], ['payload' => 'test']);

        $this->assertSame(['routed' => true], $result);
    }

    public function testSendDeleteRequest(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                return $request->getMethod() === 'DELETE'
                    && (string)$request->getUri() === 'http://rabbit:15672/api/queues/%2F/test';
            }))
            ->willReturn(new Response(204, [], ''));

        $client = new Client($httpClient, 'http://rabbit:15672');
        $result = $client->send('/api/queues/%2F/test', 'DELETE');

        $this->assertEmpty($result);
    }

    public function testSendIncludesAuthHeaders(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                $auth = $request->getHeaderLine('Authorization');
                return str_starts_with($auth, 'Basic ')
                    && base64_decode(substr($auth, 6)) === 'admin:secret';
            }))
            ->willReturn(new Response(200, [], '{"name":"admin"}'));

        $client = new Client($httpClient, 'http://rabbit:15672', 'admin', 'secret');
        $result = $client->send('/api/whoami');

        $this->assertSame(['name' => 'admin'], $result);
    }
}
