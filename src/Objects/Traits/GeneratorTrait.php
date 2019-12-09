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

namespace Splash\Connectors\Faker\Objects\Traits;

use Splash\Components\FieldsFactory;
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
    public function generateFieldsSet(string $fieldSetType)
    {
        //====================================================================//
        // Load Field Builder Service
        $this->fieldBuilder->Init($this->fieldsFactory());
        //==============================================================================
        // Populate Fields Array
        switch ($fieldSetType) {
            case 'short':
                //==============================================================================
                // Short Objects Fields Definition
                $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));

                $this->fieldBuilder->add((string) ObjectsHelper::encode('short', SPL_T_ID), array('Listed'));

//                $this->fieldBuilder->add(SPL_T_BOOL, array("Listed"));
//                $this->fieldBuilder->add(SPL_T_INT, array("Listed"));
//                $this->fieldBuilder->add(SPL_T_BOOL, array("Group" => "Group 1"));
//                $this->fieldBuilder->add(SPL_T_INT, array("Group" => "Group 1"));
//                $this->fieldBuilder->add(SPL_T_VARCHAR, array("Group" => "Group 1"));
//                $this->fieldBuilder->add(SPL_T_EMAIL, array("Listed"));
//                $this->fieldBuilder->add(SPL_T_PHONE, array("Group" => "Group 2"));
//                $this->fieldBuilder->add(SPL_T_MVARCHAR, array("Group" => "Multilang"));
//                $this->fieldBuilder->add(SPL_T_MTEXT, array("Group" => "Multilang"));

                break;
            case 'simple':
                //==============================================================================
                // Simple Objects Fields Definition
                $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
                $this->fieldBuilder->add(SPL_T_VARCHAR, array());
                $this->fieldBuilder->add(SPL_T_BOOL, array());
                $this->fieldBuilder->add(SPL_T_INT, array('Listed', 'Required'));
                $this->fieldBuilder->add(SPL_T_DOUBLE, array());
                $this->fieldBuilder->add(SPL_T_DATE, array());
                $this->fieldBuilder->add(SPL_T_DATETIME, array());
                $this->fieldBuilder->add(SPL_T_CURRENCY, array());
                $this->fieldBuilder->add(SPL_T_LANG, array());
                $this->fieldBuilder->add(SPL_T_STATE, array());
                $this->fieldBuilder->add(SPL_T_COUNTRY, array());
                $this->fieldBuilder->add(SPL_T_EMAIL, array());
                $this->fieldBuilder->add(SPL_T_URL, array());
                $this->fieldBuilder->add(SPL_T_PHONE, array());
                $this->fieldBuilder->add(SPL_T_PRICE, array('Required'));
//                $this->fieldBuilder->add(SPL_T_PRICE, []);

                break;
            case 'list':
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
//                $this->fieldBuilder->add(SPL_T_PHONE       . LISTSPLIT . SPL_T_LIST,array());
                $this->fieldBuilder->add(SPL_T_DATE.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_DATETIME.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_LANG.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_COUNTRY.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_STATE.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_URL.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_MVARCHAR.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_MTEXT.LISTSPLIT.SPL_T_LIST, array('Required'));
                $this->fieldBuilder->add(SPL_T_PRICE.LISTSPLIT.SPL_T_LIST, array('Required'));

                break;
            case 'image':
                $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
                $this->fieldBuilder->add(SPL_T_BOOL, array());
                $this->fieldBuilder->add(SPL_T_INT, array());

                //==============================================================================
                // Simple but with Image Fields Definition
                $this->fieldBuilder->add(SPL_T_IMG, array());

                break;
            case 'file':
                $this->fieldBuilder->add(SPL_T_VARCHAR, array('Listed', 'Required'));
                $this->fieldBuilder->add(SPL_T_BOOL, array());
                $this->fieldBuilder->add(SPL_T_INT, array());

                //==============================================================================
                // Simple but with File Fields Definition
                $this->fieldBuilder->add(SPL_T_FILE, array());

                break;
        }

        //==============================================================================
        // Short Objects Meta Fields Definition
        $this->fieldBuilder->addMeta(FieldsFactory::META_OBJECTID);
    }
}
