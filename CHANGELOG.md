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
