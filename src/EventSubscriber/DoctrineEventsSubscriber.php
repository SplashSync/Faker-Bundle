<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Faker\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Splash\Bundle\Connectors\Standalone;
use Splash\Bundle\Services\ConnectorsManager;
use Splash\Connectors\Faker\Connectors\FakeConnector;
use Splash\Connectors\Faker\Entity\FakeEntity;

/**
 * Faker Objects Doctrine Events Subscriber.
 */
class DoctrineEventsSubscriber implements EventSubscriber
{
    /**
     * Splash Connectors Manager
     *
     * @var ConnectorsManager
     */
    private $manager;

    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//

    /**
     * Service Constructor
     *
     * @param ConnectorsManager $manager
     */
    public function __construct(ConnectorsManager $manager)
    {
        //====================================================================//
        // Store Faker Connector Manager
        $this->manager = $manager;
    }

    //====================================================================//
    //  SUBSCRIBER
    //====================================================================//

    /**
     * Configure Event Subscriber
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        // Doctrine Events
        return array('postPersist', 'postUpdate', 'preRemove');
    }

    //====================================================================//
    //  EVENTS ACTIONS
    //====================================================================//

    /**
     * After Doctrine Persist Event
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs): void
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getObject(), SPL_A_CREATE);
    }

    /**
     * After Doctrine Update Event
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs): void
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getObject(), SPL_A_UPDATE);
    }

    /**
     * Before Doctrine Remove Event
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs): void
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getObject(), SPL_A_DELETE);
    }

    /**
     * Object Commit for All Servers using Standalone Connector
     *
     * @param object $entity
     * @param string $action
     */
    private function doCommit(object $entity, string $action): void
    {
        //====================================================================//
        //  Check Entity is A Faker Object
        if (!($entity instanceof FakeEntity)) {
            return;
        }
        //====================================================================//
        //  Search in Configured Servers using Standalone Connector
        $servers = array_merge(
            $this->manager->getConnectorConfigurations(Standalone::NAME),
            $this->manager->getConnectorConfigurations(FakeConnector::NAME),
        );
        //====================================================================//
        //  Walk on Configured Servers
        foreach (array_keys($servers) as $serverId) {
            //====================================================================//
            //  Load Connector
            $connector = $this->manager->get((string) $serverId);
            //====================================================================//
            //  Safety Check
            if ((null === $connector) || ($connector->getWebserviceId() != $entity->getWebserviceId())) {
                continue;
            }
            //====================================================================//
            //  Execute Commit
            $connector->commit(
                $entity->getType(),
                $entity->getIdentifier(),
                $action,
                'Symfony Faker',
                'Change Committed Fake '.$entity->getType()
            );
        }
    }
}
