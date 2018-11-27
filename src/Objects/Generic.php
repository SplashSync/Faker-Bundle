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
    
    // Faker Traits
    use Traits\GeneratorTrait;
    use Traits\CrudTrait;
    use Traits\ConnectorTrait;

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
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var FakeObject
     */
    protected $entity;

    /**
     * @abstract Doctrine Entity Manager
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FieldsBuilder
     */
    protected $fieldBuilder;

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
            self::lists()->initOutput($this->out, $listName, $fieldName);
            if (isset($this->object->{$listName})) {
                $this->out[$listName] = $this->object->{$listName};
            }
        } else {
            if (isset($this->object->{$fieldName})) {
                $this->out[$fieldName] = $this->object->{$fieldName};
            } else {
                $this->out[$fieldName] = null;
            }
        }
        unset($this->in[$key]);
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
        unset($this->in[$fieldName]);
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


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

}
