SlmQueue
========

[![Latest Stable Version](https://poser.pugx.org/slm/queue/v/stable.png)](https://packagist.org/packages/JouwWeb/slm-queue)

SlmQueue is a job queue abstraction layer for Laminas (formerly Zend Framework) and Mezzio (formerly Zend Expressive) applications. It supports various job queue systems and
makes your application independent from the underlying system you use. The currently supported systems have each their
own adapter-module and are the following:

* Beanstalk: use [SlmQueueBeanstalkd](https://github.com/JouwWeb/SlmQueueBeanstalkd)
* Amazon SQS: use [SlmQueueSqs](https://github.com/JouwWeb/SlmQueueSqs)
* Doctrine ORM: use [SlmQueueDoctrine](https://github.com/JouwWeb/SlmQueueDoctrine)
* RabbitMQ: use [SlmQueueRabbitMq](https://github.com/rnd-cosoft/slm-queue-rabbitmq)

A job queue helps to offload long or memory-intensive processes from the HTTP requests clients sent to the Laminas
application. This will make your response times shorter and your visitors happier. There are many use cases
for asynchronous jobs and a few examples are:

1. Send an email
2. Create a PDF file
3. Connect to a third party server over HTTP

In all cases you want to serve the response as soon as possible to your visitor, without letting them wait for this
long process. SlmQueue enables you to implement a job queue system very easily within your existing application.

Installation
------------

SlmQueue works with [Composer](http://getcomposer.org). Make sure you have the composer.phar downloaded and you have a
`composer.json` file at the root of your project. To install it, add the following line into your `composer.json` file:

```json
"require": {
    "slm/queue": "^3.0"
}
```

After installation of the package, you need to complete the following steps to use SlmQueue:

 1. Enable the module by adding `SlmQueue` in your `application.config.php` file.
 2. Copy the `slm_queue.global.php.dist` (you can find this file in the `config` folder of SlmQueue) into
your `config/autoload` folder and apply any setting you want.

NB. SlmQueue is a skeleton and therefore useless by itself. Enable an adapter to give you the implementation details
you need to push jobs into the queue. Choose one of the available adapters
[SlmQueueBeanstalkd](https://github.com/JouwWeb/SlmQueueBeanstalkd),
[SlmQueueSqs](https://github.com/JouwWeb/SlmQueueSqs)
or [SlmQueueDoctrine](https://github.com/JouwWeb/SlmQueueDoctrine)

Requirements
------------
* PHP >= 7.3
* [laminas-servicemanager >= 3.3.1](https://github.com/laminas/laminas-servicemanager)


Code samples
------------
Below are a few snippets which show the power of SlmQueue in your application. The full documentation is available in
[docs/](/docs) directory.

A sample job to send an email with php's `mail()` might look like this:

```php
namespace MyModule\Job;

use SlmQueue\Job\AbstractJob;

class EmailJob extends AbstractJob
{
    public static function create(string $to, string $subject, string $message): self
    {
        // This will bypass the constructor, and thus load a job without
        // having to load the dependencies.
        $job = self::createEmptyJob([
            'subject' => $subject,
            'to' => $to,
            'message' => $message,
        ]);

        // Add some metadata, so we see what is going on.
        $job->setMetadata('to', $to);

        return $job;
    }

    private SomeMailService $mailService;

    public function __construct(SomeMailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function execute()
    {
        $payload = $this->getContent();

        $to      = $payload['to'];
        $subject = $payload['subject'];
        $message = $payload['message'];

        $this->mailService->send($to, $subject, $message);
    }
}
```

If you want to inject this job into a queue, you can do this for instance in your controller:

```php
namespace MyModule\Controller;

use MyModule\Job\Email as EmailJob;
use SlmQueue\Queue\QueueInterface;
use Laminas\Mvc\Controller\AbstractActionController;

class MyController extends AbstractActionController
{
    protected $queue;

    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    public function fooAction()
    {
        // Do some work

        $this->queue->push(
            EmailJob::create('john@doe.com', 'Just hi', 'Hi, I want to say hi!'),
            ['delay' => 60]
        );
    }
}
```

Now the above code lets you insert jobs in a queue, but then you need to spin up a worker which can process these jobs.
Giving an example with beanstalkd and a queue which you called "default", you can start a worker with this command:

    php public/index.php queue beanstalkd default

### Slm short circuit queue config example

Shortcut queue does not add job into queue - instead, it executes it instantly.
This allows one to conveniently debug SLM jobs as they were part of the same application run instance.
Just add this config, then set a breakpoint in your Job class and debug it!

APPLICATION/`config/autoload/slm.local.php`:
```php
<?php

use SlmQueue\Factory\ShortCircuitQueueFactory;

return [
    'slm_queue' => [
        'queue_manager' => [
            'factories' => [
                'some_queue_name' => ShortCircuitQueueFactory::class,
            ],
        ],
    ],
];
```

Contributing
------------

SlmQueue is developed by various fanatic Laminas users. The code is written to be as generic as possible for
Laminas applications. If you want to contribute to SlmQueue, fork this repository and start hacking!

Any bugs can be reported as an [issue](https://github.com/JouwWeb/SlmQueue/issues) at GitHub. If you want to
contribute, please be aware of the following guidelines:

 1. Fork the project to your own repository
 2. Use branches to work on your own part
 3. Create a Pull Request at the canonical SlmQueue repository
 4. Make sure to cover changes with the right amount of unit tests
 5. If you add a new feature, please work on some documentation as well

For long-term contributors, push access to this repository is granted.

Who to thank?
-------------

[Jurian Sluiman](https://github.com/juriansluiman) and [Michaël Gallego](https://github.com/bakura10) did the initial work on creating this repo, and maintained it for a long time.

Currently it is maintained by:

* [Bas Kamer](https://github.com/basz)
* [Roel van Duijnhoven](https://github.com/roelvanduijnhoven)
