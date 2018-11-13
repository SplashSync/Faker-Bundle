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

use Splash\Bundle\Events\Standalone\ActionsListingEvent;
use Splash\Bundle\Events\Standalone\FormListingEvent;
use Splash\Bundle\Events\Standalone\ObjectsListingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Description of FakerEventsSuscriber.
 *
 * @author nanard33
 */
class SymfonyEventsSubscriber implements EventSubscriberInterface
{
    /**
     * @abstract    Faker Bundle Configuration
     *
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
        $this->config = $Configuration;
    }

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
        return [
            // Standalone Events
            ObjectsListingEvent::NAME => [
                ['onObjectListing', 0],
            ],
            ActionsListingEvent::NAME => [
                ['onActionsListing', 0],
            ],
            FormListingEvent::NAME => [
                ['onFormListing', 0],
            ],
        ];
    }

    //====================================================================//
    //  EVENTS ACTIONS
    //====================================================================//

    /**
     * @abstract    On Standalone Object Listing Event
     *
     * @param ObjectsListingEvent $event
     */
    public function onObjectListing(ObjectsListingEvent $event)
    {
        //====================================================================//
        // Walk on Configuration to Add Objects
        foreach ($this->config['objects'] as $Object) {
            $event->addObjectType($Object['id'], 'splash.connector.faker.object.'.$Object['id']);
        }
    }

    /**
     * @abstract    On Standalone Actions Listing Event
     *
     * @param ActionsListingEvent $event
     */
    public function onActionsListing(ActionsListingEvent $event)
    {
        $event->addAction('index', 'SplashFakerBundle:Actions:index');
        $event->addAction('validate', 'SplashFakerBundle:Actions:validate');
        $event->addAction('invalidate', 'SplashFakerBundle:Actions:invalidate');
        $event->addAction('fail', 'SplashFakerBundle:Actions:fail');

        $event->addAction('noClass', 'SplashFakerBundle:Error:index');
        $event->addAction('noClass2', 'SplashFakerError:Actions:index');
        $event->addAction('noAction', 'SplashFakerBundle:Actions:error');
//        $event->addAction("defaults",   "SplashFakerBundle:Actions:index",  );
    }

    /**
     * @abstract    On Standalone Form Listing Event => Populate Edit Form
     *
     * @param FormListingEvent $event
     */
    public function onFormListing(FormListingEvent $event)
    {
        //====================================================================//
        // Add Option to Disable Objects
        foreach ($this->config['objects'] as $Object) {
            $event->getBuilder()
                ->add('faker_disable_'.$Object['id'], CheckboxType::class, [
                    'label' => $Object['name'].' Disable Test Mode',
                    'required' => false,
                ])
            ;
        }
    }
}
