<?php

namespace SlmQueue\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\ShortCircuitQueue;

class ShortCircuitQueueFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return ShortCircuitQueue
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $jobPluginManager = $container->get(JobPluginManager::class);

        return new ShortCircuitQueue($requestedName, $jobPluginManager);
    }
}

