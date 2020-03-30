<?php

namespace SlmQueue\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SlmQueue\Controller\Plugin\QueuePlugin;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\QueuePluginManager;

class QueueControllerPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): QueuePlugin
    {
        $queuePluginManager = $container->get(QueuePluginManager::class);
        $jobPluginManager = $container->get(JobPluginManager::class);

        return new QueuePlugin($queuePluginManager, $jobPluginManager);
    }
}
