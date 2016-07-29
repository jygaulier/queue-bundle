<?php

/*
 * This file is part of queue-bundle.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\QueueBundle\Queue;

use Alchemy\Queue\Amqp\AmqpMessageQueueFactory;
use Alchemy\Queue\MessageQueue;

class QueueRegistry
{
    /**
     * @var array
     */
    private $configurations = [];

    /**
     * @var MessageQueue[]
     */
    private $queues = [];

    /**
     * @param string $queueName
     * @param array $configuration
     */
    public function bindConfiguration($queueName, array $configuration)
    {
        $this->configurations[$queueName] = $configuration;
    }

    /**
     * @param string $queueName
     * @return MessageQueue
     */
    public function getQueue($queueName)
    {
        if (isset($this->queues[$queueName])) {
            return $this->queues[$queueName];
        }

        if (isset($this->configurations[$queueName])) {
            return $this->queues[$queueName] =
                AmqpMessageQueueFactory::create($this->configurations[$queueName])->getNamedQueue($queueName);
        }

        throw new \RuntimeException('Queue is not registered: ' . $queueName);
    }

}
