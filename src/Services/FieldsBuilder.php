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

namespace Splash\Connectors\Faker\Services;

use Splash\Components\FieldsFactory;

/**
 * Fake Nodes Fields Builder Service
 */
class FieldsBuilder
{
    /**
     * Fields Types Counter
     *
     * @var array
     */
    private array $counters = array();

    /**
     * Splash Fields Factory
     *
     * @var FieldsFactory
     */
    private FieldsFactory $fieldsFactory;

    /**
     * Setup Splash Field Factory
     *
     * @param FieldsFactory $factory Splash objects Fields Factory
     *
     * @return self
     */
    public function init(FieldsFactory $factory): self
    {
        //====================================================================//
        // Initialize Splash Field Factory Class
        $this->fieldsFactory = $factory;
        //====================================================================//
        // Clear Fields Counters
        $this->counters = array();

        return $this;
    }

    /**
     * Return Field Factory Data
     *
     * @return null|array
     */
    public function publish(): ?array
    {
        return $this->fieldsFactory->publish();
    }

    //====================================================================//
    //  COMMON FUNCTIONS
    //====================================================================//

    /**
     * Increment Field Type Counter
     *
     * @param mixed $type
     *
     * @return int New Value
     */
    public function count($type): int
    {
        if (!isset($this->counters[$type])) {
            $this->counters[$type] = 0;
        }
        ++$this->counters[$type];

        return (int) $this->counters[$type];
    }

    /**
     * Add Field to FieldFactory
     *
     * @param string     $fieldType
     * @param null|array $options
     *
     * @return $this
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function add(string $fieldType, array $options = null): self
    {
        //==============================================================================
        // Init Parameters
        $count = $this->count($fieldType);
        $name = preg_replace('/:/', '', $fieldType.$count);
        //==============================================================================
        // Add Field Core Infos
        $this->fieldsFactory->create($fieldType)
            ->identifier((string) $name)
            ->name(strtoupper((string) $name))
            ->description('Fake Field - Type '.strtoupper($fieldType).' Item '.$count)
            ->microData('http://fake.schema.org/'.$fieldType, $fieldType.$count)
        ;

        //==============================================================================
        // No Options   => Exit
        if (null === $options) {
            return $this;
        }
        //==============================================================================
        // Setup Options
        if (isset($options['Group']) && is_scalar($options['Group'])) {
            $this->fieldsFactory->group((string) $options['Group']);
        }
        if (\in_array('Required', $options, true)) {
            $this->fieldsFactory->isRequired();
        }
        if (\in_array('Primary', $options, true)) {
            $this->fieldsFactory->isPrimary();
        }
        if (\in_array('Listed', $options, true)) {
            $this->fieldsFactory->isListed();
        }
        if (\in_array('ListHidden', $options, true)) {
            $this->fieldsFactory->isListHidden();
        }
        if (\in_array('Logged', $options, true)) {
            $this->fieldsFactory->isLogged();
        }
        if (\in_array('ReadOnly', $options, true)) {
            $this->fieldsFactory->isReadOnly();
        }
        if (\in_array('WriteOnly', $options, true)) {
            $this->fieldsFactory->isWriteOnly();
        }

        return $this;
    }

    /**
     * Add Meta Field to FieldFactory
     *
     * @param string     $metaType
     * @param null|array $options
     *
     * @return self
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function addMeta(string $metaType, array $options = null): self
    {
        //==============================================================================
        // Init Parameters
        $count = $this->count($metaType);
        $name = 'm_'.$metaType.$count;
        //==============================================================================
        //      Detect Meta Data Field Type
        switch ($metaType) {
            //==============================================================================
            //      OPENOBJECT => Mongo ObjectId
            case FieldsFactory::META_OBJECTID:
                //==============================================================================
                //      OPENOBJECT => Creation Date
            case FieldsFactory::META_DATECREATED:
                //==============================================================================
                //      OPENOBJECT => Source Node Id
            case FieldsFactory::META_ORIGIN_NODE_ID:
                $fieldType = SPL_T_VARCHAR;

                break;
                //==============================================================================
                //      UNKNOWN => Exit
            default:
                return $this;
        }
        //==============================================================================
        // Add Field Core Infos
        $this->fieldsFactory->create($fieldType)
            ->identifier($name)
            ->name(strtoupper($name))
            ->description('Fake Field - Meta Type '.strtoupper($metaType).' Item '.$count)
            ->microData(FieldsFactory::META_URL, $metaType)
        ;
        //==============================================================================
        // No Options   => Exit
        if (null === $options) {
            return $this;
        }
        //==============================================================================
        // Setup Options
        if (isset($options['Group']) && is_scalar($options['Group'])) {
            $this->fieldsFactory->group((string) $options['Group']);
        }
        if (isset($options['Required'])) {
            $this->fieldsFactory->isRequired();
        }
        if (isset($options['Listed'])) {
            $this->fieldsFactory->isListed();
        }
        if (isset($options['Logged'])) {
            $this->fieldsFactory->isLogged();
        }
        if (isset($options['ReadOnly'])) {
            $this->fieldsFactory->isReadOnly();
        }
        if (isset($options['WriteOnly'])) {
            $this->fieldsFactory->isWriteOnly();
        }

        return $this;
    }

    /**
     * Compare Two Fields Definition Array
     *
     * @param array $source
     * @param array $target
     *
     * @return bool
     */
    public function compare(array $source, array $target): bool
    {
        //==============================================================================
        // Compare Each Array Row
        foreach ($source as $key => $value) {
            //==============================================================================
            // Compare Simple Rows
            if (!\is_array($value) && ($target[$key] !== $value)) {
                return false;
            }
            if (!\is_array($value)) {
                continue;
            }
            //==============================================================================
            // Compare Array Rows
            if (empty($value) && empty($target[$key])) {
                continue;
            }
            if ($this->compare($value, $target[$key])) {
                continue;
            }

            return false;
        }

        return true;
    }
}
