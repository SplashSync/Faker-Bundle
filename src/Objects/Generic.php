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
    private $entity;

    /**
     * @abstract Doctrine Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FieldsBuilder
     */
    private $fieldBuilder;

    //====================================================================//
    // Service Constructor
    //====================================================================//

    /**
     * @abstract    Service Constructor
     *
     * @param FieldsBuilder          $fieldsBuilder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(FieldsBuilder $fieldsBuilder, EntityManagerInterface $entityManager)
    {
        //====================================================================//
        // Link to Fake Fields Builder Services
        $this->fieldBuilder = $fieldsBuilder;
        //====================================================================//
        // Link to Doctrine Entity Manager Services
        $this->entityManager = $entityManager;
    }

    //====================================================================//
    // Service SelfTest
    //====================================================================//

    /**
     * @abstract    Execute Self Test for This Object
     *
     * @return bool
     */
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

    /**
     * @abstract    Execute Ping Test for This Object
     *
     * @return bool
     */
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
     *  @param        string $key       Input List Key
     *  @param        string $fieldName Field Identifier / Name
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function getCoreFields($key, $fieldName)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);

        //====================================================================//
        // Detect List Fields
        $listName = self::lists()->listName($fieldName);
        if ($listName) {
            self::lists()->initOutput($this->Out, $listName, $fieldName);
            if (isset($this->Object->{$listName})) {
                $this->Out[$listName] = $this->Object->{$listName};
            }
        } else {
            if (isset($this->Object->{$fieldName})) {
                $this->Out[$fieldName] = $this->Object->{$fieldName};
            } else {
                $this->Out[$fieldName] = null;
            }
        }
        unset($this->In[$key]);
    }

    /**
     *  @abstract     Write Given Fields
     *
     *  @param        string $fieldName Field Identifier / Name
     *  @param        mixed  $data      Field Data
     */
    public function setCoreFields($fieldName, $data)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);
        //====================================================================//
        // Read Data
        $this->setSimple($fieldName, $data);
        unset($this->In[$fieldName]);
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
        $Repo = $this->entityManager->getRepository('SplashFakerBundle:FakeObject');

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
     * @param string $objectId Object id
     *
     * @return mixed
     */
    public function load($objectId)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace(__CLASS__, __FUNCTION__);
        //====================================================================//
        // Search in Repository
        /** @var null|FakeObject $Entity */
        $Entity = $this->entityManager
            ->getRepository('SplashFakerBundle:FakeObject')
            ->findOneBy([
                'type' => $this->type,
                'identifier' => $objectId,
            ]);
        //====================================================================//
        // Check Object Entity was Found
        if (!$Entity) {
            return Splash::log()->err(
                'ErrLocalTpl',
                __CLASS__,
                __FUNCTION__,
                ' Unable to load '.$this->name.' ('.$objectId.').'
            );
        }
        $this->entity = $Entity;

        return new ArrayObject(
            \is_array($this->entity->getData()) ? $this->entity->getData() : [],
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
        $this->entity = new FakeObject();
        $this->entity->setType($this->type);
        $this->entity->setIdentifier(uniqid());
        $this->entity->setData([]);

        //====================================================================//
        // Persist (but DO NOT FLUSH)
        $this->entityManager->persist($this->entity);

        return new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @abstract    Update Request Object
     *
     * @param array $needed Is This Update Needed
     *
     * @return string Object Id
     */
    public function update($needed)
    {
        //====================================================================//
        // Save
        if ($needed) {
            $this->entity->setData($this->Object->getArrayCopy());
            $this->entityManager->flush();
        }

        return $this->entity->getIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($objectId = null)
    {
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
     * @param string $fieldSetType
     *
     * @return array
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
