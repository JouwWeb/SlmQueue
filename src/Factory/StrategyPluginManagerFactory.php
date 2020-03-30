<?php

namespace SlmQueue\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SlmQueue\Strategy\StrategyPluginManager;

class StrategyPluginManagerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): StrategyPluginManager {
        $config = $container->get('config');
        $config = $config['slm_queue']['strategy_manager'];

        return new StrategyPluginManager($container, $config);
    }
}
