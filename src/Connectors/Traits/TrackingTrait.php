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

namespace Splash\Connectors\Faker\Connectors\Traits;

use Splash\Connectors\Faker\Dictionary\FakeObjectsTypes;

trait TrackingTrait
{
    /**
     * @inheritDoc
     */
    public function isObjectTracked(string $objectType): bool
    {
        return (FakeObjectsTypes::TRACKING == $objectType);
    }

    /**
     * @inheritDoc
     */
    public function getObjectTrackingDelay(string $objectType): int
    {
        return $this->getGenericObject($objectType)->getTrackingDelay();
    }

    /**
     * @inheritDoc
     */
    public function getObjectUpdatedIds(string $objectType): array
    {
        return $this->getGenericObject($objectType)->getUpdatedIds();
    }

    /**
     * @inheritDoc
     */
    public function getObjectDeletedIds(string $objectType): array
    {
        return $this->getGenericObject($objectType)->getDeletedIds();
    }
}
