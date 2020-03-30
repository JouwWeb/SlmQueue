<?php

namespace SlmQueue\Strategy\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use SlmQueue\Strategy\LogJobStrategy;

class LogJobStrategyFactory implements FactoryInterface
{
    protected $options;

    public function __construct(array $options = null)
    {
        $this->options = $options;
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LogJobStrategy
    {
        return new LogJobStrategy($container->get('console'), $this->options);
    }
}
