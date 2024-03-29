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

namespace Splash\Connectors\Faker\Objects\Traits;

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
    public function selftest(): bool
    {
        if ($this->getParameter('faker_disable_'.$this->getSplashType(), false)) {
            return true;
        }
        if (!$this->getParameter('faker_validate_selftest', false)) {
            return  Splash::log()
                ->err($this->getName().' : Faker Self-test Not Validated... Use config page to validate it!');
        }

        return  Splash::log()->msg($this->getName().' Self-test Object Passed');
    }

    //====================================================================//
    // Profiles for Testing
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function getConnectedTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->getSplashType(), false)) {
            return '';
        }

        return '@Faker/connected.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getOfflineTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->getSplashType(), false)) {
            return '';
        }

        return '@Faker/offline.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getNewTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->getSplashType(), false)) {
            return '';
        }

        return '@Faker/new.html.twig';
    }
}
