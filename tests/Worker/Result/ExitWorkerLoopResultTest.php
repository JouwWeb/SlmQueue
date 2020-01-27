<?php

namespace SlmQueueTest\Worker\Result;

use PHPUnit\Framework\TestCase as TestCase;
use SlmQueue\Worker\Result\ExitWorkerLoopResult;

class ExitWorkerLoopResultTest extends TestCase
{
    public function testGetsReason()
    {
        $reason = 'some reason';
        $result = ExitWorkerLoopResult::withReason($reason);

        static::assertEquals($reason, $result->getReason());
    }
}
