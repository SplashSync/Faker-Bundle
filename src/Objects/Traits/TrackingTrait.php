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

use Splash\Connectors\Faker\Entity\FakeEntity;
use Splash\Connectors\Faker\Repository\FakeEntityRepository;

trait TrackingTrait
{
    /**
     * @inheritDoc
     */
    public function getTrackingDelay(): int
    {
        return 60;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedIds(): array
    {
        /** @var FakeEntityRepository $repository */
        $repository = $this->entityManager->getRepository(FakeEntity::class);
        //====================================================================//
        // Execute Query
        $data = $repository->getTrackedUpdated($this->getWebserviceId(), $this->getSplashType());

        //====================================================================//
        // Map on Object Ids
        return array_map(function (FakeEntity $fakeEntity): string {
            return $fakeEntity->getIdentifier();
        }, $data);
    }

    /**
     * @inheritDoc
     */
    public function getDeletedIds(): array
    {
        /** @var FakeEntityRepository $repository */
        $repository = $this->entityManager->getRepository(FakeEntity::class);
        //====================================================================//
        // Execute Query
        $data = $repository->getTrackedDeleted($this->getWebserviceId(), $this->getSplashType());

        //====================================================================//
        // Map on Object Ids
        return array_map(function (FakeEntity $fakeEntity): string {
            return $fakeEntity->getIdentifier();
        }, $data);
    }
}
