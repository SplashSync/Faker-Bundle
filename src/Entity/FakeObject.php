<?php

namespace Splash\Connectors\FakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @abstract    Splash Fake/Testing Objects
 *
 * @ORM\Table(name="splash__faker__objects")
 * @ORM\Entity(repositoryClass="Splash\Connectors\FakerBundle\Repository\FakeObjectRepository")
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
    
    public function __toString()
    {
        return $this->getType()."@".$this->getIdentifier();
    }
    
    //==============================================================================
    //      GETTERS & SETTERS
    //==============================================================================
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set identifier
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
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set Field
     *
     * @param   string    $ObjectId Object Id
     * @param   \stdClass $Data     Object Field Data
     *
     * @return $this
     */
    public function setField(string $ObjectId, $Data)
    {
        $this->data[$ObjectId] = $Data;

        return $this;
    }
    
    /**
     * Set data
     *
     * @param   array $Data
     *
     * @return  self
     */
    public function setData($Data)
    {
        //====================================================================//
        // Raw Write of Object Data
        $this->data = $Data;

        return $this;
    }

    /**
     * Get data
     *
     * @param   string $FieldId Field Name or Null
     *
     * @return array|string|null
     */
    public function getData($FieldId = null)
    {
        if ($FieldId) {
            if (!isset($this->data[$FieldId])) {
                return null;
            }

            return $this->data[$FieldId];
        }

        return $this->data;
    }

    /**
     * Set condition
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
     * Get condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }
}
