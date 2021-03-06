<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Faker\Objects\Traits;

use ArrayObject;
use Splash\Client\Splash;
use Splash\Connectors\Faker\Entity\FakeObject;

/**
 * Generic Faker Objects CRUD
 */
trait CrudTrait
{
    //====================================================================//
    // Generic Objects CRUD Functions
    //====================================================================//

    /**
     * Load Request Object
     *
     * @param string $objectId Object id
     *
     * @return ArrayObject|false
     */
    public function load($objectId)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Search in Repository
        /** @var null|FakeObject $entity */
        $entity = $this->entityManager
            ->getRepository(FakeObject::class)
            ->findOneBy(array(
                'type' => $this->getSplashType(),
                'identifier' => $objectId,
            ));
        //====================================================================//
        // Check Object Entity was Found
        if (!$entity) {
            return Splash::log()->errTrace(
                ' Unable to load '.$this->getName().' ('.$objectId.').'
            );
        }
        $this->entity = $entity;

        return new ArrayObject(
            \is_array($this->entity->getData()) ? $this->entity->getData() : array(),
            ArrayObject::ARRAY_AS_PROPS
        );
    }

    /**
     * Create Request Object
     *
     * @return ArrayObject New Object
     */
    public function create(): ArrayObject
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();

        //====================================================================//
        // Create New Entity
        $this->entity = new FakeObject();
        $this->entity->setType($this->getSplashType());
        $this->entity->setIdentifier(uniqid());
        $this->entity->setData(array());

        //====================================================================//
        // Persist (but DO NOT FLUSH)
        $this->entityManager->persist($this->entity);

        return new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Update Request Object
     *
     * @param bool $needed Is This Update Needed
     *
     * @return false|string Object Id
     */
    public function update($needed)
    {
        //====================================================================//
        // Save
        if ($needed) {
            $this->entity->setData($this->object->getArrayCopy());
            $this->entityManager->flush();
        }

        return $this->getObjectIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($objectId = null): bool
    {
        //====================================================================//
        // Safety Check
        if (null == $objectId) {
            return true;
        }
        //====================================================================//
        // Try Loading Object to Check if Exists
        if ($this->load($objectId)) {
            //====================================================================//
            // Delete
            $this->entityManager->remove($this->entity);
            $this->entityManager->flush();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectIdentifier()
    {
        if (empty($this->entity->getIdentifier())) {
            return false;
        }

        return $this->entity->getIdentifier();
    }
}
