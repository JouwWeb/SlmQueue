<?php

namespace SlmQueue\Strategy\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use SlmQueue\Strategy\AttachQueueListenersStrategy;
use SlmQueue\Strategy\StrategyPluginManager;

class AttachQueueListenersStrategyFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): AttachQueueListenersStrategy {
        $pluginManager = $container->get(StrategyPluginManager::class);
        $config = $container->get('config');
        $strategyConfig = $config['slm_queue']['worker_strategies']['queues'];

        return new AttachQueueListenersStrategy($pluginManager, $strategyConfig);
    }
}
