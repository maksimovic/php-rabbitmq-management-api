<?php

declare(strict_types=1);

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Channel;

class ChannelTest extends AbstractApiTestCase
{
    private Channel $channel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->channel = new Channel($this->client);
    }

    public function testAll(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/channels')
            ->willReturn([]);

        $this->assertSame([], $this->channel->all());
    }

    public function testGet(): void
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with('/api/channels/ch1')
            ->willReturn([]);

        $this->assertSame([], $this->channel->get('ch1'));
    }
}
