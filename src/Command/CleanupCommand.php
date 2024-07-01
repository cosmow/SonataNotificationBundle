<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\NotificationBundle\Command;

use Sonata\NotificationBundle\Backend\BackendInterface;
use Sonata\NotificationBundle\Backend\QueueDispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * @final since sonata-project/notification-bundle 3.13
 */
class CleanupCommand extends Command
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('sonata:notification:cleanup');
        $this->setDescription('Clean up backend message');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('<info>Starting ... </info>');

        $this->getBackend()->cleanup();

        $output->writeln('done!');

        return 0;
    }

    /**
     * @return BackendInterface
     */
    private function getBackend()
    {
        $backend = $this->container->get('sonata.notification.backend');

        if ($backend instanceof QueueDispatcherInterface) {
            return $backend->getBackend(null);
        }

        return $backend;
    }
}
