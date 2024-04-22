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

use Splash\Components\FieldsFactory;
use Splash\Connectors\Faker\Dictionary\FakeObjectsTypes as Types;
use Splash\Models\Helpers\ObjectsHelper;

/**
 * Faker Generic Object Fields Generator
 */
trait GeneratorTrait
{
    //====================================================================//
    // Objects Fields Set Generator
    //====================================================================//

    /**
     * Generate Fake Node Field
     *
     * @param string $fieldSetType
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function generateFieldsSet(string $fieldSetType): void
    {
        //====================================================================//
        // Load Field Builder Service
        $this->fieldBuilder->init($this->fieldsFactory());
        //==============================================================================
        // Populate Fields
        match ($fieldSetType) {
            Types::TRACKING, Types::SHORT => $this->generateShortObjectFields(),
            default => $this->generateSimpleObjectFields(),
            Types::PRIMARY => $this->generatePrimaryObjectFields(),
            Types::OBJECTS => $this->generateObjectsObjectFields(),
            Types::LIST => $this->generateListObjectFields(),
            Types::IMAGE => $this->generateImageObjectFields(),
            Types::FILE => $this->generateFileObjectFields(),
            Types::STREAM => $this->generateStreamObjectFields(),
        };

        //==============================================================================
        // Add Mandatory Objects Meta Fields Definition
        $this->fieldBuilder->addMeta(FieldsFactory::META_ORIGIN_NODE_ID);
        $this->fieldBuilder->addMeta(FieldsFactory::META_ORIGIN_NODE_NAME);
        $this->fieldBuilder->addMeta(FieldsFactory::META_OBJECTID);
        $this->fieldBuilder->addMeta(FieldsFactory::META_DATECREATED);
    }

    /**
     * Generate Short Fake Node Object Fields
     */
    private function generateShortObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
        $this->fieldBuilder->add((string) ObjectsHelper::encode('short', SPL_T_ID), array('Listed'));
    }

    /**
     * Generate Simple Fake Node Object Fields
     */
    private function generateSimpleObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('ListHidden'));
        $this->fieldBuilder->add(SPL_T_BOOL, array());
        $this->fieldBuilder->add(SPL_T_INT, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_DOUBLE, array());
        $this->fieldBuilder->add(SPL_T_DATE, array("Group" => "Group 1"));
        $this->fieldBuilder->add(SPL_T_DATETIME, array("Group" => "Group 1"));
        $this->fieldBuilder->add(SPL_T_CURRENCY, array("Group" => "Group 1"));
        $this->fieldBuilder->add(SPL_T_LANG, array("Group" => "Group 1"));
        $this->fieldBuilder->add(SPL_T_STATE, array("Group" => "Group 1"));
        $this->fieldBuilder->add(SPL_T_COUNTRY, array("Group" => "Group 1"));
        $this->fieldBuilder->add(SPL_T_EMAIL, array("Group" => "Group 2"));
        $this->fieldBuilder->add(SPL_T_URL, array("Group" => "Group 2"));
        $this->fieldBuilder->add(SPL_T_PHONE, array("Group" => "Group 2"));
        $this->fieldBuilder->add(SPL_T_INLINE, array("Group" => "Group 2"));
        $this->fieldBuilder->add(SPL_T_PRICE, array('Required', "Group" => "Group 2"));
    }

    /**
     * Generate Primary Fake Node Object Fields
     */
    private function generatePrimaryObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required', 'Primary'));
        $this->fieldBuilder->add((string) ObjectsHelper::encode('short', SPL_T_ID), array('Listed'));
        $this->fieldBuilder->add(SPL_T_BOOL, array());
        $this->fieldBuilder->add(SPL_T_INT, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_DOUBLE, array());
    }

    /**
     * Generate Objects Fake Node Object Fields
     */
    private function generateObjectsObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_INT, array());
        $this->fieldBuilder->add(SPL_T_BOOL, array());
        foreach (Types::getAll() as $objectType) {
            $this->fieldBuilder->add((string) ObjectsHelper::encode($objectType, SPL_T_ID));
        }
    }

    /**
     * Generate List Fake Node Object Fields
     */
    private function generateListObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_BOOL, array());
        $this->fieldBuilder->add(SPL_T_INT, array());

        //==============================================================================
        // Simple List Objects Fields Definition
        $this->fieldBuilder->add(SPL_T_BOOL.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_INT.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_DOUBLE.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_VARCHAR.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_TEXT.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_EMAIL.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_DATE.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_DATETIME.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_LANG.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_COUNTRY.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_STATE.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_URL.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_MVARCHAR.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_MTEXT.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_INLINE.LISTSPLIT.SPL_T_LIST, array('Required'));
        $this->fieldBuilder->add(SPL_T_PRICE.LISTSPLIT.SPL_T_LIST, array('Required'));
    }

    /**
     * Generate Image Fake Node Object Fields
     */
    private function generateImageObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_BOOL, array());
        $this->fieldBuilder->add(SPL_T_INT, array());

        //==============================================================================
        // Simple but with Image Fields Definition
        $this->fieldBuilder->add(SPL_T_IMG, array());
    }

    /**
     * Generate File Fake Node Object Fields
     */
    private function generateFileObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_BOOL, array());
        $this->fieldBuilder->add(SPL_T_INT, array());

        //==============================================================================
        // Simple but with File Fields Definition
        $this->fieldBuilder->add(SPL_T_FILE, array());
    }

    /**
     * Generate Stream Fake Node Object Fields
     */
    private function generateStreamObjectFields(): void
    {
        $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
        $this->fieldBuilder->add(SPL_T_BOOL, array());
        $this->fieldBuilder->add(SPL_T_INT, array());

        //==============================================================================
        // Simple but with Stream Fields Definition
        $this->fieldBuilder->add(SPL_T_STREAM, array());
    }
}
