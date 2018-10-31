<?php

/**
 * This file is part of SplashSync Project.
 *
 * Copyright (C) Splash Sync <www.splashsync.com>
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Bernard Paquier <contact@splashsync.com>
 */

namespace Splash\Connectors\FakerBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Splash\Bundle\Events\ObjectsListingEvent;
use Splash\Bundle\Events\ActionsListingEvent;

/**
 * Description of FakerEventsSuscriber
 *
 * @author nanard33
 */
class SymfonyEventsSuscriber implements EventSubscriberInterface
{
    
    /**
     * @abstract    Faker Bundle Configuration
     * @var array
     */
    private $config;
    
    //====================================================================//
    //  CONSTRUCTOR
    //====================================================================//
    
    /**
     * @abstract    Service Constructor
     */
    public function __construct(array $Configuration)
    {
        //====================================================================//
        // Store Faker Service Configuration
        $this->config       =   $Configuration;
    }
    
    //====================================================================//
    //  SUBSCRIBER
    //====================================================================//
    
    /**
     * @abstract    Configure Event Subscriber
     * @return  void
     */
    public static function getSubscribedEvents()
    {
        return array(
            // Standalone Events
            ObjectsListingEvent::NAME   => array(
               array('onObjectListing', 0)
            ),
            ActionsListingEvent::NAME   => array(
               array('onActionsListing', 0)
            )
        );
    }

    //====================================================================//
    //  EVENTS ACTIONS
    //====================================================================//

    /**
     * @abstract    On Standalone Object Listing Event
     * @param   ObjectsListingEvent $event
     * @return  void
     */
    public function onObjectListing(ObjectsListingEvent $event)
    {
        //====================================================================//
        // Walk on Configuration to Add Objects
        foreach ($this->config["objects"] as $Object) {
            $event->addObjectType($Object["id"], "splash.connector.faker.object." . $Object["id"]);
        }
    }
    
    /**
     * @abstract    On Standalone Actions Listing Event
     * @param   ActionsListingEvent $event
     * @return  void
     */
    public function onActionsListing(ActionsListingEvent $event)
    {
        $event->addAction("index",      "SplashFakerBundle:Actions:index");
        $event->addAction("dummy",      "SplashFakerBundle:Actions:dummy");
        $event->addAction("fail",       "SplashFakerBundle:Actions:fail");
        
        $event->addAction("noClass",    "SplashFakerBundle:Error:index");
        $event->addAction("noClass2",   "SplashFakerError:Actions:index");
        $event->addAction("noAction",   "SplashFakerBundle:Actions:error");
    }    
}
