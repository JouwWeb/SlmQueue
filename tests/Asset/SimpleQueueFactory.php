<?php

namespace SlmQueueTest\Asset;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use SlmQueue\Job\JobPluginManager;

class SimpleQueueFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SimpleQueue
    {
        $jobPluginManager = new JobPluginManager($container);

        return new SimpleQueue($requestedName, $jobPluginManager);
    }
}
