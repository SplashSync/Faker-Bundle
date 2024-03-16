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

namespace Splash\Connectors\Faker\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Splash\Connectors\Faker\Repository\FakeEntityRepository;

/**
 * Splash Fake/Testing Objects
 */
#[ORM\Table("splash__faker__objects")]
#[ORM\Entity(repositoryClass: FakeEntityRepository::class)]
class FakeEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    /**
     * Entity Version
     */
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Version]
    private int $version;

    //==============================================================================
    //      FAKER OBJECT DATA
    //==============================================================================

    /**
     * Fake Server Webservice ID
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $webserviceId;

    /**
     * Fake Object Type Name
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $type;

    /**
     * Fake Object Identifier
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $identifier;

    /**
     * Fake Object Data
     */
    #[ORM\Column(type: Types::ARRAY)]
    private array $data = array();

    //==============================================================================
    //      DATA OPERATIONS
    //==============================================================================

    /**
     * Convert Object to String
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
     * Get ID.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * Set Webserver ID.
     */
    public function setWebserviceId(string $webserviceId): static
    {
        $this->webserviceId = $webserviceId;

        return $this;
    }

    /**
     * Get Webserver ID.
     */
    public function getWebserviceId(): string
    {
        return $this->webserviceId;
    }

    /**
     * Set Object Type.
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Object Type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set Object Identifier.
     */
    public function setIdentifier(string $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get Object Identifier.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Set Field.
     *
     * @param string            $fieldId    Object ID
     * @param null|array|scalar $objectData Object Field Data
     *
     * @return $this
     */
    public function setField(string $fieldId, $objectData): self
    {
        $this->data[$fieldId] = $objectData;

        return $this;
    }

    /**
     * Set data.
     *
     * @param array<null|array|scalar> $objectData
     *
     * @return self
     */
    public function setData(array $objectData): self
    {
        //====================================================================//
        // Raw Write of Object Data
        $this->data = $objectData;

        return $this;
    }

    /**
     * Get data.
     *
     * @param null|string $fieldId Field Name or Null
     *
     * @return null|array|scalar
     */
    public function getData(string $fieldId = null)
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
     * Set Entity Version.
     */
    public function setCondition(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get Entity Version.
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }
}
