<?php

namespace SlmQueue\Queue;

use SlmQueue\Job\JobInterface;

class ShortCircuitQueue extends AbstractQueue implements QueueInterface
{
    /**
     * @inheritDoc
     */
    public function push(JobInterface $job, array $options = []): void
    {
        $job->execute();
    }

    /**
     * @inheritDoc
     */
    public function pop(array $options = []): ?JobInterface
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function delete(JobInterface $job): void
    {
    }
}
