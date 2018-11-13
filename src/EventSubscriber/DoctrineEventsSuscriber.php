<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @author Bernard Paquier <contact@splashsync.com>
 */

namespace Splash\Connectors\FakerBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Splash\Bundle\Services\ConnectorsManager;
use Splash\Connectors\FakerBundle\Entity\FakeObject;

/**
 * Description of FakerEventsSuscriber.
 *
 * @author nanard33
 */
class DoctrineEventsSuscriber implements EventSubscriber
{
    /**
     * @abstract    Faker Bundle Configuration
     *
     * @var array
     */
    private $Config;

    /**
     * @abstract    Splash Connectors Manager
     *
     * @var ConnectorsManager
     */
    private $Manager;

    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//

    /**
     * @abstract    Service Constructor
     */
    public function __construct(array $Configuration, ConnectorsManager $Manager)
    {
        //====================================================================//
        // Store Faker Service Configuration
        $this->Config = $Configuration;
        //====================================================================//
        // Store Faker Connector Manager
        $this->Manager = $Manager;
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
        return ['postPersist', 'postUpdate', 'preRemove'];
    }

    //====================================================================//
    //  EVENTS ACTIONS
    //====================================================================//

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getEntity(), SPL_A_CREATE);
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getEntity(), SPL_A_UPDATE);
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        //====================================================================//
        //  Submit Change
        $this->doCommit($eventArgs->getEntity(), SPL_A_DELETE);
    }

    private function doCommit($Entity, $Action)
    {
        //====================================================================//
        //  Check Entity is A Faker Object
        if (FakeObject::class !== \get_class($Entity)) {
            return;
        }
        //====================================================================//
        //  Search in Configured Servers using Standalone Connector
        $Servers = $this->Manager->getConnectorConfigurations('splash.connectors.standalone');
        //====================================================================//
        //  Walk on Configured Servers
        foreach (array_keys($Servers) as $ServerId) {
            //====================================================================//
            //  Load Connector
            $Connector = $this->Manager->get((string) $ServerId);
            //====================================================================//
            //  Safety Check
            if (null === $Connector) {
                continue;
            }
            //====================================================================//
            //  Execute Commit
            $Connector->commit(
                $Entity->getType(),
                $Entity->getIdentifier(),
                $Action,
                'Symfony Faker',
                'Change Commited Fake '.$Entity->getType()
            );
        }
    }
}
