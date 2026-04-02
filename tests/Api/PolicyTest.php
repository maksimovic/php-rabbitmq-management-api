<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Policy;

class PolicyTest extends AbstractApiTestCase
{
    private Policy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new Policy($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/policies')
            ->willReturn([]);

        $this->assertSame([], $this->policy->all());
    }

    public function testGetByVhost(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/policies/%2F')
            ->willReturn([]);

        $this->assertSame([], $this->policy->get('/'));
    }

    public function testGetByVhostAndName(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/policies/%2F/ha-all')
            ->willReturn([]);

        $this->assertSame([], $this->policy->get('/', 'ha-all'));
    }

    public function testCreate(): void
    {
        $policyDef = ['pattern' => '.*', 'definition' => ['ha-mode' => 'all']];

        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/policies/%2F/ha-all', 'PUT', [], $policyDef)
            ->willReturn([]);

        $this->assertSame([], $this->policy->create('/', 'ha-all', $policyDef));
    }

    public function testDelete(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/policies/%2F/ha-all', 'DELETE')
            ->willReturn([]);

        $this->assertSame([], $this->policy->delete('/', 'ha-all'));
    }
}
