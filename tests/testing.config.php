<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */
return array(
    'service_manager' => array(
        'factories' => array(
            'SlmQueue\Job\JobPluginManager'     => 'SlmQueue\Factory\JobPluginManagerFactory',
            'SlmQueue\Queue\QueuePluginManager' => 'SlmQueue\Factory\QueuePluginManagerFactory'
        )
    ),

    'slm_queue' => array(
        /**
         * Worker config
         */
        'worker' => array(
            'max_runs'   => 100000,
            'max_memory' => 1024
        ),

        /**
         * Jobs config
         */
        'jobs' => array(),

        /**
         * Queues config
         */
        'queues' => array(
            'factories' => array(
                'basic-queue' => function($locator) {
                    $parentLocator    = $locator->getServiceLocator();
                    $jobPluginManager = $parentLocator->get('SlmQueue\Job\JobPluginManager');

                    return new \SlmQueueTest\Asset\SimpleQueue('basic-queue', $jobPluginManager);
                }
            )
        )
    ),
);
