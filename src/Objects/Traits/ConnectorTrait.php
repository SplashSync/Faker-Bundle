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

namespace Splash\Connectors\FakerBundle\Objects\Traits;

use Splash\Client\Splash;

/**
 * @abstract    Faker Generic Object Connector Functions
 */
trait ConnectorTrait
{

    //====================================================================//
    // Service SelfTest
    //====================================================================//

    /**
     * @abstract    Execute Self Test for This Object
     *
     * @return bool
     */
    public function selftest()
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return true;
        }
        if (!$this->getParameter('faker_validate_selftest', false)) {
            return  Splash::log()
                ->Err($this->getName().' : Faker Selftest Not Validated... Use config page to validate it!');
        }

        return  Splash::log()->Msg('Faker '.$this->getName().' Object Passed');
    }

    //====================================================================//
    // Service Connect
    //====================================================================//

    /**
     * @abstract    Execute Ping Test for This Object
     *
     * @return bool
     */
    public function connect()
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return true;
        }
        if (!$this->getParameter('faker_validate_connect', false)) {
            return  Splash::log()
                ->Err($this->getName().'Faker Connect Not Validated... Use config page to validate it!');
        }

        return  Splash::log()->Msg('Faker '.$this->getName().' Object Passed');
    }

    //====================================================================//
    // Profiles for Testing
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function getConnectedTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return '';
        }

        return '@SplashFaker/connected.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getOfflineTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return '';
        }

        return '@SplashFaker/offline.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getNewTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return '';
        }

        return '@SplashFaker/new.html.twig';
    }
}
