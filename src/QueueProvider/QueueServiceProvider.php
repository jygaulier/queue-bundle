<?php

namespace Alchemy\QueueProvider;

use Alchemy\Queue\MessageQueueRegistry;
use Silex\Application;
use Silex\ServiceProviderInterface;

class QueueServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app['alchemy_queues.registry'] = $app->share(function (Application $app) {
            return new MessageQueueRegistry();
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        foreach ($app['alchemy_queues.queues'] as $name => $configuration) {
            /** @var MessageQueueRegistry $targetRegistry */
            $targetRegistry = $app['alchemy_queues.registry'];

            if (isset($configuration['registry'])) {
                $targetRegistry = $app[$configuration['registry']];
            }

            $targetRegistry->bindConfiguration($name, $configuration);
        }
    }
}
