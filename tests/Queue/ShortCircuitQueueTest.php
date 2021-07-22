<?php

namespace SlmQueueTest\Queue;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\ShortCircuitQueue;

class ShortCircuitQueueTest extends TestCase
{
    /** @var ShortCircuitQueue */
    private $shortCircuitQueue;

    /** @var JobPluginManager|MockObject */
    private $jobPluginManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->jobPluginManager = $this->createMock(JobPluginManager::class);

        $this->shortCircuitQueue = new ShortCircuitQueue('some_queue_name', $this->jobPluginManager);
    }

    public function testPush(): void
    {
        $job = $this->createMock(JobInterface::class);
        $job->expects($this->once())->method('execute');

        $this->shortCircuitQueue->push($job);
    }

    public function testPop(): void
    {
        $job = $this->shortCircuitQueue->pop();

        $this->assertNull($job);
    }
}
