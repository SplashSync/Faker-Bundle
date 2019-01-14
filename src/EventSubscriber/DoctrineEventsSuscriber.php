<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2019 Splash Sync  <www.splashsync.com>
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
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Splash\Bundle\Connectors\Standalone;
use Splash\Bundle\Services\ConnectorsManager;
use Splash\Connectors\Faker\Entity\FakeObject;

/**
 * Description of FakerEventsSuscriber.
 *
 * @author nanard33
 */
class DoctrineEventsSuscriber implements EventSubscriber
{
    /**
     * @abstract    Splash Connectors Manager
     *
     * @var ConnectorsManager
     */
    private $manager;

    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//

    /**
     * @abstract    Service Constructor
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
     * @abstract    Configure Event Subscriber
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        // Doctrine Events
        return array('postPersist', 'postUpdate', 'preRemove');
    }

    //====================================================================//
    //  EVENTS ACTIONS
    //====================================================================//

    /**
     * @abstract    After Doctrine Persist Event
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getEntity(), SPL_A_CREATE);
    }

    /**
     * @abstract    After Doctrine Update Event
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getEntity(), SPL_A_UPDATE);
    }

    /**
     * @abstract    Before Doctrine Remove Event
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getEntity(), SPL_A_DELETE);
    }

    /**
     * @abstract    Object Commit for All Servers using Standalone Connector
     *
     * @param object $entity
     * @param string $action
     */
    private function doCommit($entity, $action)
    {
        //====================================================================//
        //  Check Entity is A Faker Object
        if (!($entity instanceof FakeObject)) {
            return;
        }
        //====================================================================//
        //  Search in Configured Servers using Standalone Connector
        $servers = $this->manager->getConnectorConfigurations(Standalone::NAME);
        //====================================================================//
        //  Walk on Configured Servers
        foreach (array_keys($servers) as $serverId) {
            //====================================================================//
            //  Load Connector
            $connector = $this->manager->get((string) $serverId);
            //====================================================================//
            //  Safety Check
            if (null === $connector) {
                continue;
            }
            //====================================================================//
            //  Execute Commit
            $connector->commit(
                $entity->getType(),
                $entity->getIdentifier(),
                $action,
                'Symfony Faker',
                'Change Commited Fake '.$entity->getType()
            );
        }
    }
}
