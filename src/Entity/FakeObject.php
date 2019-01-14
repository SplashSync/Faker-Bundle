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

namespace Splash\Connectors\Faker\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @abstract    Splash Fake/Testing Objects
 *
 * @ORM\Table(name="splash__faker__objects")
 * @ORM\Entity(repositoryClass="Splash\Connectors\Faker\Repository\FakeObjectRepository")
 */
class FakeObject
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     */
    private $condition;

    //==============================================================================
    //      FAKER OBJECT DATA
    //==============================================================================

    /**
     * @abstract    Fake Object Type Name
     *
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @abstract    Fake Object Identifier
     *
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @abstract    Fake Object Data
     *
     * @var array
     *
     * @ORM\Column(name="data", type="object")
     */
    private $data;

    //==============================================================================
    //      DATA OPERATIONS
    //==============================================================================

    /**
     * @abstract Convert Object to String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getType().'@'.$this->getIdentifier();
    }

    //==============================================================================
    //      GETTERS & SETTERS
    //==============================================================================

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set identifier.
     *
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set Field.
     *
     * @param string    $objectId   Object Id
     * @param \stdClass $objectData Object Field Data
     *
     * @return $this
     */
    public function setField(string $objectId, $objectData)
    {
        $this->data[$objectId] = $objectData;

        return $this;
    }

    /**
     * Set data.
     *
     * @param array $objectData
     *
     * @return self
     */
    public function setData($objectData)
    {
        //====================================================================//
        // Raw Write of Object Data
        $this->data = $objectData;

        return $this;
    }

    /**
     * Get data.
     *
     * @param string $fieldId Field Name or Null
     *
     * @return null|array|string
     */
    public function getData($fieldId = null)
    {
        if ($fieldId) {
            if (!isset($this->data[$fieldId])) {
                return null;
            }

            return $this->data[$fieldId];
        }

        return $this->data;
    }

    /**
     * Set condition.
     *
     * @param string $condition
     *
     * @return self
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition.
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }
}
