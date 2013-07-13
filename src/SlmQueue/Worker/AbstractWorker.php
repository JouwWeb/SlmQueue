<?php

namespace SlmQueue\Worker;

use SlmQueue\Job\JobInterface;
use SlmQueue\Options\WorkerOptions;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Queue\QueuePluginManager;
use SlmQueue\Queue\QueueAwareInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * AbstractWorker
 */
abstract class AbstractWorker implements WorkerInterface, EventManagerAwareInterface
{
    /**
     * Event constants
     */
    const EVENT_PROCESS_QUEUE_PRE  = 'processQueue.pre';
    const EVENT_PROCESS_QUEUE_POST = 'processQueue.post';
    const EVENT_PROCESS_JOB_PRE    = 'processJob.pre';
    const EVENT_PROCESS_JOB_POST   = 'processJob.post';

    /**
     * @var QueuePluginManager
     */
    protected $queuePluginManager;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * @var WorkerOptions
     */
    protected $options;


    /**
     * Constructor
     *
     * @param QueuePluginManager $queuePluginManager
     * @param WorkerOptions      $options
     */
    public function __construct(QueuePluginManager $queuePluginManager, WorkerOptions $options)
    {
        $this->queuePluginManager = $queuePluginManager;
        $this->options            = $options;

        // Listen to the signals SIGTERM and SIGINT so that the worker can be killed properly. Note that
        // because pcntl_signal may not be available on Windows, we needed to check for the existence of the function
        if (function_exists('pcntl_signal')) {
            declare(ticks = 1);
            pcntl_signal(SIGTERM, array($this, 'handleSignal'));
            pcntl_signal(SIGINT,  array($this, 'handleSignal'));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function processQueue($queueName, array $options = array())
    {
        /** @var $queue QueueInterface */
        $queue        = $this->queuePluginManager->get($queueName);
        $eventManager = $this->getEventManager();
        $count        = 0;

        $workerEvent = new WorkerEvent();
        $workerEvent->setQueue($queue);

        $eventManager->trigger(WorkerEvent::EVENT_PROCESS_QUEUE_PRE, $workerEvent);

        while (true) {
            // Check for external stop condition
            if ($this->isStopped()) {
                break;
            }

            $job = $queue->pop($options);

            // The queue may return null, for instance if a timeout was set
            if (!$job instanceof JobInterface) {
                continue;
            }

            // The job might want to get the queue injected
            if ($job instanceof QueueAwareInterface) {
                $job->setQueue($queue);
            }

            $workerEvent->setJob($job);

            $eventManager->trigger(WorkerEvent::EVENT_PROCESS_JOB_PRE, $workerEvent);

            $this->processJob($job, $queue);
            $count++;

            $eventManager->trigger(WorkerEvent::EVENT_PROCESS_JOB_POST, $workerEvent);

            // Check for internal stop condition
            if (
                $count === $this->options->getMaxRuns()
                || memory_get_usage() > $this->options->getMaxMemory()
            ) {
                break;
            }
        }

        $eventManager->trigger(WorkerEvent::EVENT_PROCESS_QUEUE_POST, $workerEvent);

        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(
            get_called_class(),
            'SlmQueue\Worker\WorkerInterface'
        ));

        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    /**
     * Check if the script has been stopped from a signal
     *
     * @return bool
     */
    public function isStopped()
    {
        return $this->stopped;
    }

    /**
     * Handle the signal
     *
     * @param int $signo
     */
    public function handleSignal($signo)
    {
        switch($signo) {
            case SIGTERM:
            case SIGINT:
                $this->stopped = true;
                break;
        }
    }
}
