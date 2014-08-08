<?php
namespace SlmQueue\Listener\Strategy\Factory;

use SlmQueue\Listener\Strategy\LogJobStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * LogJobStrategyFactory
 */
class LogJobStrategyFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return LogJobStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $strategy = new LogJobStrategy($serviceLocator->get('Console'));

        return $strategy;
    }
}
