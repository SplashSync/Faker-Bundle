<?php

namespace Splash\Connectors\FakerBundle\Services;

use ArrayObject;

use Splash\Components\FieldsFactory;

/**
 * @abstract    Fake Nodes Fields Builder Service
 */
class FieldsBuilder
{

    /**
     * @abstract    Fields Types Counter
     *
     * @var     array
     */
    private $counters = array();
    
    /**
     * @abstract    Splash Fields Factory
     *
     * @var FieldsFactory
     */
    private $FieldsFactory = null;

    /**
     * @abstract    Setup Spash Field Factory
     *
     * @return self
     */
    public function init(FieldsFactory $Factory)
    {
        //====================================================================//
        // Initialize Splash Field Factory Class
        $this->FieldsFactory    =   $Factory;
        //====================================================================//
        // Clear Fields Counters
        $this->counters = array();
        
        return $this;
    }
    
    /**
     * @abstract    Return Field Factory Data
     *
     * @return      ArrayObject[]|false
     */
    public function publish()
    {
        return $this->FieldsFactory->Publish();
    }

    
    
    //====================================================================//
    //  COMMON FUNCTIONS
    //====================================================================//

    /**
     * @abstract    Increment Field Type Counter
     *
     * @return int  New Value
     */
    public function count($Type)
    {
        if (!isset($this->counters[$Type])) {
            $this->counters[$Type]  = 0;
        }
        $this->counters[$Type]++;

        return $this->counters[$Type];
    }
    
    /**
     * @abstract    Add Field to FieldFactory
     *
     * @param string $FieldType
     * @param array  $Options
     *
     * @return self
     */
    public function add(string $FieldType, $Options = null)
    {
        //==============================================================================
        // Init Parameters
        $Count  =   $this->count($FieldType);
        $Name   =   preg_replace('/:/', '', $FieldType.$Count);
        //==============================================================================
        // Add Field Core Infos
        $this->FieldsFactory->Create($FieldType)
                    ->Identifier((string) $Name)
                    ->Name(strtoupper((string) $Name))
                    ->Description("Fake Field - Type ".strtoupper($FieldType)." Item ".$Count)
                    ->MicroData("http://fake.schema.org/".$FieldType, $FieldType.$Count);
                        
        //==============================================================================
        // No Options   => Exit
        if (is_null($Options)) {
            return $this;
        }
        //==============================================================================
        // Setup Options
        if (isset($Options["Group"]) && is_scalar($Options["Group"])) {
            $this->FieldsFactory->group((string) $Options["Group"]);
        }
        if (in_array("Required", $Options)) {
            $this->FieldsFactory->isRequired();
        }
        if (in_array("Listed", $Options)) {
            $this->FieldsFactory->isListed();
        }
        if (in_array("Logged", $Options)) {
            $this->FieldsFactory->isLogged();
        }
        if (in_array("ReadOnly", $Options)) {
            $this->FieldsFactory->isReadOnly();
        }
        if (in_array("WriteOnly", $Options)) {
            $this->FieldsFactory->isWriteOnly();
        }
        
        return $this;
    }
    
    /**
     * @abstract    Add Meta Field to FieldFactory
     *
     * @param string $MetaType
     * @param array  $Options
     *
     * @return self
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function addMeta($MetaType, $Options = null)
    {
        //==============================================================================
        // Init Parameters
        $Count  =   $this->count($MetaType);
        $Name   =   "m_".$MetaType.$Count;
        
        //==============================================================================
        //      Detect Meta Data Field Type
        switch ($MetaType) {
            //==============================================================================
            //      OPENOBJECT => Mongo ObjectId
            case FieldsFactory::META_OBJECTID:
            //==============================================================================
            //      OPENOBJECT => Creation Date
            case FieldsFactory::META_DATECREATED:
            //==============================================================================
            //      OPENOBJECT => Source Node Id
            case FieldsFactory::META_OBJECTID:
                $FieldType = SPL_T_VARCHAR;
                break;
            //==============================================================================
            //      UNKNOWN => Exit
            default:
                return $this;
        }
        
        //==============================================================================
        // Add Field Core Infos
        $this->FieldsFactory->Create($FieldType)
                    ->Identifier($Name)
                    ->Name(strtoupper($Name))
                    ->Description("Fake Field - Meta Type ".strtoupper($MetaType)." Item ".$Count)
                    ->MicroData(FieldsFactory::META_URL, $MetaType);
                        
        //==============================================================================
        // No Options   => Exit
        if (is_null($Options)) {
            return $this;
        }
        //==============================================================================
        // Setup Options
        if (isset($Options["Group"]) && is_scalar($Options["Group"])) {
            $this->FieldsFactory->group((string) $Options["Group"]);
        }
        if (isset($Options["Required"])) {
            $this->FieldsFactory->isRequired();
        }
        if (isset($Options["Listed"])) {
            $this->FieldsFactory->isListed();
        }
        if (isset($Options["Logged"])) {
            $this->FieldsFactory->isLogged();
        }
        if (isset($Options["ReadOnly"])) {
            $this->FieldsFactory->isReadOnly();
        }
        if (isset($Options["WriteOnly"])) {
            $this->FieldsFactory->isWriteOnly();
        }
        
        return $this;
    }
    
    /**
     * @abstract    Compare Two Fields Definition Array
     *
     * @param array $Source
     * @param array $Target
     *
     * @return bool
     */
    public function compare($Source, $Target)
    {
        
        //==============================================================================
        // Compare Each Array Row
        foreach ($Source as $key => $value) {
            //==============================================================================
            // Compare Simple Rows
            if (!is_array($value) && ($Target[$key] != $value)) {
                return false;
            } elseif (!is_array($value)) {
                continue;
            }
            //==============================================================================
            // Compare Array Rows
            if (empty($value) && empty($Target[$key])) {
                continue;
            }
            if ($this->compare($value, $Target[$key])) {
                continue;
            }

            return false;
        }
        
        return true;
    }
}
