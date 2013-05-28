# 0.3.0

- BC: composer package has been changed from "juriansluiman/slm-queue" to "slm/queue". Remember to update
your `composer.json` file!
- BC: AbstractJob constructor is now gone. It simplifies injecting dependencies as you do not need to remember
to call parent constructor.
- BC: keys for configuring queues was previously "queues", it is now "queue_manager". The key "queues" is still used
but it's now for specifying options for a specific queue.
- Job metadata is now serialized
- You can make your jobs implement the interface 'SlmQueue\Queue\QueueAwareInterface'. Therefore, you will have
access to the queue in the `execute` method of the job.
- All exceptions now implement `SlmQueue\Exception\ExceptionInterface`, so you can easily filter exceptions.

# 0.2.5

- Change the visibility of the handleSignal function in the worker as it caused a problem
- Fix a bug that may occur on Windows machines

# 0.2.4

- Add support for signals to stop worker properly

# 0.2.3

- Fix compatibilities problems with PHP 5.3

# 0.2.2

- Fix compatibilities problems with PHP 5.3

# 0.2.1

- Fix the default memory limit of the worker (from 1KB, which was obviously wrong, to 100MB)

# 0.2.0

- This version is a complete rewrite of SlmQueue. It is now splitted in several modules and support both
Beanstalkd and Amazon SQS queue systems through SlmQueueBeanstalkd and SlmQueueSqs modules.
