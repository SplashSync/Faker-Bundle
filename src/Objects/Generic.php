<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @author Bernard Paquier <contact@splashsync.com>
 */

namespace Splash\Connectors\FakerBundle\Objects;

use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\Components\FieldsFactory;
use Splash\Connectors\FakerBundle\Entity\FakeObject;
use Splash\Connectors\FakerBundle\Repository\FakeObjectRepository;
use Splash\Connectors\FakerBundle\Services\FieldsBuilder;
use Splash\Models\Helpers\ObjectsHelper;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\SimpleFieldsTrait;

/**
 * Description of Generic.
 *
 * @author nanard33
 */
class Generic extends AbstractStandaloneObject
{
    // Splash Php Core Traits
    use IntelParserTrait;
    use SimpleFieldsTrait;
    use ListsTrait;

    //====================================================================//
    // Object Definition Parameters
    //====================================================================//

    /**
     *  Object Disable Flag. Uncomment thius line to Override this flag and disable Object.
     */
//    protected static    $DISABLED        =  True;

    /**
     *  Object Description (Translated by Module).
     */
    protected static $DESCRIPTION = 'Faker Object';

    /**
     *  Object Icon (FontAwesome or Glyph ico tag).
     */
    protected static $ICO = 'fa fa-magic';

    //====================================================================//
    // Private variables
    //====================================================================//

    /**
     *  @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $format;

    /**
     * @var FakeObject
     */
    private $Entity;

    /**
     * @abstract Doctrine Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $EntityManager;

    /**
     * @var FieldsBuilder
     */
    private $fieldBuilder;

    //====================================================================//
    // Service Constructor
    //====================================================================//

    public function __construct(FieldsBuilder $FieldsBuilder, EntityManagerInterface $EntityManager)
    {
        //====================================================================//
        // Link to Fake Fields Builder Services
        $this->fieldBuilder = $FieldsBuilder;
        //====================================================================//
        // Link to Doctrine Entity Manager Services
        $this->EntityManager = $EntityManager;
    }

    //====================================================================//
    // Service SelfTest
    //====================================================================//

    public function selftest()
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return true;
        }
        if (!$this->getParameter('faker_validate_selftest', false)) {
            return  Splash::log()
                ->Err($this->getName().' : Faker Selftest Not Validated... Use config page to validate it!');
        }

        return  Splash::log()->Msg('Faker '.$this->getName().' Object Passed');
    }

    //====================================================================//
    // Service Connect
    //====================================================================//

    public function connect()
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return true;
        }
        if (!$this->getParameter('faker_validate_connect', false)) {
            return  Splash::log()
                ->Err($this->getName().'Faker Connect Not Validated... Use config page to validate it!');
        }

        return  Splash::log()->Msg('Faker '.$this->getName().' Object Passed');
    }

    //====================================================================//
    // Profiles for Testing
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function getConnectedTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return '';
        }

        return '@SplashFaker/connected.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getOfflineTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return '';
        }

        return '@SplashFaker/offline.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getNewTemplate(): string
    {
        if ($this->getParameter('faker_disable_'.$this->type, false)) {
            return '';
        }

        return '@SplashFaker/new.html.twig';
    }

    //====================================================================//
    // Service Configuration
    //====================================================================//

    /**
     * @param string $type
     * @param string $name
     * @param string $format
     */
    public function setConfiguration(string $type, string $name, string $format)
    {
        $this->type = $type;
        $this->name = $name;
        $this->format = $format;
    }

    //====================================================================//
    // Generic Objects Functions (See Splash PhpCore IntelliParser)
    //====================================================================//

    /**
     * @abstract     Build Core Fields using FieldFactory
     */
    public function buildCoreFields()
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);
        //====================================================================//
        // Generate Fake Fields
        $this->generateFieldsSet($this->format);
    }

    /**
     *  @abstract     Read requested Field
     *
     *  @param        string $Key       Input List Key
     *  @param        string $FieldName Field Identifier / Name
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function getCoreFields($Key, $FieldName)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);
        //====================================================================//
        // Read Data

        //====================================================================//
        // Detect List Fields
        $Listname = self::lists()->listName($FieldName);
        if ($Listname) {
            self::lists()->initOutput($this->Out, $Listname, $FieldName);
            if (isset($this->Object->{$Listname})) {
                $this->Out[$Listname] = $this->Object->{$Listname};
            }
        } else {
            if (isset($this->Object->{$FieldName})) {
                $this->Out[$FieldName] = $this->Object->{$FieldName};
            } else {
                $this->Out[$FieldName] = null;
            }
        }
        unset($this->In[$Key]);
    }

    /**
     *  @abstract     Write Given Fields
     *
     *  @param        string $FieldName Field Identifier / Name
     *  @param        mixed  $Data      Field Data
     */
    public function setCoreFields($FieldName, $Data)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);
        //====================================================================//
        // Read Data
        $this->setSimple($FieldName, $Data);
        unset($this->In[$FieldName]);
    }

    /**
     * {@inheritdoc}
     */
    public function objectsList($filter = null, $params = null)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);

        $Response = [];
        /** @var FakeObjectRepository $Repo */
        $Repo = $this->EntityManager->getRepository('SplashFakerBundle:FakeObject');

        //====================================================================//
        // Prepare List Filters List
        $Search = [
            'type' => $this->type,
        ];
        if (!empty($filter)) {
            $Search['identifier'] = $filter;
        }
        //====================================================================//
        // Load Objects List
        $Data = $Repo->findBy(
            $Search,
            [],
            isset($params['max']) ? $params['max'] : null,
            isset($params['offset']) ? $params['offset'] : null
        );

        //====================================================================//
        // Load Object Fields
        $Fields = $this->fields();

        //====================================================================//
        // Parse Data on Result Array
        /** @var FakeObject $Object */
        foreach ($Data as $Object) {
            $ObjectData = [
                'id' => $Object->getIdentifier(),
            ];

            foreach ($Fields as $Field) {
                if ($Field['inlist']) {
                    $ObjectData[$Field['id']] = $Object->getData($Field['id']);
                }
            }

            $Response[] = $ObjectData;
        }

        //====================================================================//
        // Parse Meta Infos on Result Array
        $Response['meta'] = [
            'total' => $Repo->getTypeCount($this->type, $filter),
            'current' => \count($Data),
        ];

        //====================================================================//
        // Return result
        return $Response;
    }

    //====================================================================//
    // Generic Objects CRUD Functions
    //====================================================================//

    /**
     * @abstract    Load Request Object
     *
     * @param string $ObjectId Object id
     *
     * @return mixed
     */
    public function load($ObjectId)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);
        //====================================================================//
        // Search in Repository
        /** @var null|FakeObject $Entity */
        $Entity = $this->EntityManager
            ->getRepository('SplashFakerBundle:FakeObject')
            ->findOneBy([
                'type' => $this->type,
                'identifier' => $ObjectId,
            ]);
        //====================================================================//
        // Check Object Entity was Found
        if (!$Entity) {
            return Splash::log()->err(
                'ErrLocalTpl',
                __CLASS__,
                __FUNCTION__,
                ' Unable to load '.$this->name.' ('.$ObjectId.').'
            );
        }
        $this->Entity = $Entity;

        return new ArrayObject(
            \is_array($this->Entity->getData()) ? $this->Entity->getData() : [],
            ArrayObject::ARRAY_AS_PROPS
        );
    }

    /**
     * @abstract    Create Request Object
     *
     * @return ArrayObject New Object
     */
    public function create()
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);

        //====================================================================//
        // Create New Entity
        $this->Entity = new FakeObject();
        $this->Entity->setType($this->type);
        $this->Entity->setIdentifier(uniqid());
        $this->Entity->setData([]);

        //====================================================================//
        // Persist (but DO NOT FLUSH)
        $this->EntityManager->persist($this->Entity);

        return new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @abstract    Update Request Object
     *
     * @param array $Needed Is This Update Needed
     *
     * @return string Object Id
     */
    public function update($Needed)
    {
        //====================================================================//
        // Save
        if ($Needed) {
            $this->Entity->setData($this->Object->getArrayCopy());
            $this->EntityManager->flush();
        }

        return $this->Entity->getIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id = null)
    {
        //====================================================================//
        // Try Loading Object to Check if Exists
        if ($this->load($id)) {
            //====================================================================//
            // Delete
            $this->EntityManager->remove($this->Entity);
            $this->EntityManager->flush();
        }

        return true;
    }

    public function getName()
    {
        return $this->name;
    }

    //====================================================================//
    // Objects Fields Set Generator
    //====================================================================//

    /**
     * @abstract    Generate Fake Node Field
     *
     * @param string $FieldSetType
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function generateFieldsSet(string $FieldSetType)
    {
        //====================================================================//
        // Load Field Builder Service
        $this->fieldBuilder->Init($this->fieldsFactory());
        //==============================================================================
        // Populate Fields Array
        switch ($FieldSetType) {
            case 'short':
                //==============================================================================
                // Short Objects Fields Definition
                $this->fieldBuilder->add(SPL_T_VARCHAR, ['Listed', 'Required']);

                $this->fieldBuilder->add(ObjectsHelper::encode('short', SPL_T_ID), ['Listed']);

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
                $this->fieldBuilder->add(SPL_T_VARCHAR, ['Listed', 'Required']);
                $this->fieldBuilder->add(SPL_T_VARCHAR, []);
                $this->fieldBuilder->add(SPL_T_BOOL, []);
                $this->fieldBuilder->add(SPL_T_INT, ['Listed', 'Required']);
                $this->fieldBuilder->add(SPL_T_DOUBLE, []);
                $this->fieldBuilder->add(SPL_T_DATE, []);
                $this->fieldBuilder->add(SPL_T_DATETIME, []);
                $this->fieldBuilder->add(SPL_T_CURRENCY, []);
                $this->fieldBuilder->add(SPL_T_LANG, []);
                $this->fieldBuilder->add(SPL_T_STATE, []);
                $this->fieldBuilder->add(SPL_T_COUNTRY, []);
                $this->fieldBuilder->add(SPL_T_EMAIL, []);
                $this->fieldBuilder->add(SPL_T_URL, []);
                $this->fieldBuilder->add(SPL_T_PHONE, []);
                $this->fieldBuilder->add(SPL_T_PRICE, ['Required']);
                $this->fieldBuilder->add(SPL_T_PRICE, []);

                break;
            case 'list':
                $this->fieldBuilder->add(SPL_T_VARCHAR, ['Listed', 'Required']);
                $this->fieldBuilder->add(SPL_T_BOOL, []);
                $this->fieldBuilder->add(SPL_T_INT, []);

                //==============================================================================
                // Simple List Objects Fields Definition
                $this->fieldBuilder->add(SPL_T_BOOL.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_INT.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_DOUBLE.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_VARCHAR.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_TEXT.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_EMAIL.LISTSPLIT.SPL_T_LIST, []);
//                $this->fieldBuilder->add(SPL_T_PHONE       . LISTSPLIT . SPL_T_LIST,array());
                $this->fieldBuilder->add(SPL_T_DATE.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_DATETIME.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_LANG.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_COUNTRY.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_STATE.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_URL.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_MVARCHAR.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_MTEXT.LISTSPLIT.SPL_T_LIST, []);
                $this->fieldBuilder->add(SPL_T_PRICE.LISTSPLIT.SPL_T_LIST, ['Required']);

                break;
            case 'image':
                $this->fieldBuilder->add(SPL_T_VARCHAR, ['Listed', 'Required']);
                $this->fieldBuilder->add(SPL_T_BOOL, []);
                $this->fieldBuilder->add(SPL_T_INT, []);

                //==============================================================================
                // Simple but with Image Fields Definition
                $this->fieldBuilder->add(SPL_T_IMG, []);

                break;
            case 'file':
                $this->fieldBuilder->add(SPL_T_VARCHAR, ['Listed', 'Required']);
                $this->fieldBuilder->add(SPL_T_BOOL, []);
                $this->fieldBuilder->add(SPL_T_INT, []);

                //==============================================================================
                // Simple but with File Fields Definition
                $this->fieldBuilder->add(SPL_T_FILE, []);

                break;
        }

        //==============================================================================
        // Short Objects Meta Fields Definition
        $this->fieldBuilder->addMeta(FieldsFactory::META_OBJECTID);
    }
}
