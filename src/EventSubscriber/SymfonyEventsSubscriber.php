<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2018 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\FakerBundle\EventSubscriber;

//use Splash\Bundle\Events\Standalone\ActionsListingEvent;
use Splash\Bundle\Events\Standalone\FormListingEvent;
//use Splash\Bundle\Events\Standalone\ObjectsListingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Description of FakerEventsSuscriber.
 *
 * @author nanard33
 */
class SymfonyEventsSubscriber implements EventSubscriberInterface
{
    //====================================================================//
    //  SUBSCRIBER
    //====================================================================//

    /**
     * @abstract    Configure Event Subscriber
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            FormListingEvent::NAME => array(
                array('onFormListing', 0),
            ),
        );
    }

    //====================================================================//
    //  EVENTS ACTIONS
    //====================================================================//

    /**
     * @abstract    On Standalone Form Listing Event => Populate Edit Form
     *
     * @param FormListingEvent $event
     */
    public function onFormListing(FormListingEvent $event)
    {
        //====================================================================//
        // Add Fake Option
        $event->getBuilder()
            ->add('faker_option', CheckboxType::class, array(
                'label' => "Click Me I'm a Fake!!",
                'required' => false,
            ))
        ;
    }
}
