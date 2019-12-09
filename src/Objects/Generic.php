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

namespace Splash\Connectors\Faker\Objects;

use Doctrine\ORM\EntityManagerInterface;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\Connectors\Faker\Entity\FakeObject;
use Splash\Connectors\Faker\Repository\FakeObjectRepository;
use Splash\Connectors\Faker\Services\FieldsBuilder;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\SimpleFieldsTrait;

/**
 * Generic Faker Object.
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
     * {@inheritdoc}
     */
    protected static $DESCRIPTION = 'Faker Object';

    /**
     * {@inheritdoc}
     */
    protected static $ICO = 'fa fa-magic';

    //====================================================================//
    // Private variables
    //====================================================================//

    /**
     * @var FakeObject
     */
    protected $entity;

    /**
     * Doctrine Entity Manager
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
     * Service Constructor
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
    // Generic Objects Functions (See Splash PhpCore IntelliParser)
    //====================================================================//

    /**
     * Build Core Fields using FieldFactory
     *
     * @return void
     */
    public function buildCoreFields()
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        //====================================================================//
        // Generate Fake Fields
        $this->generateFieldsSet($this->getSplashType());
    }

    /**
     * Read requested Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function getCoreFields($key, $fieldName)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();

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
     * Write Given Fields
     *
     * @param string $fieldName Field Identifier / Name
     * @param mixed  $data      Field Data
     *
     * @return void
     */
    public function setCoreFields($fieldName, $data)
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
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
        Splash::log()->trace();

        $response = array();
        /** @var FakeObjectRepository $repository */
        $repository = $this->entityManager->getRepository(FakeObject::class);

        //====================================================================//
        // Prepare List Filters List
        $search = array(
            'type' => $this->getSplashType(),
        );
        if (!empty($filter)) {
            $search['identifier'] = $filter;
        }
        //====================================================================//
        // Load Objects List
        $data = $repository->findBy(
            $search,
            array(),
            isset($params['max']) ? $params['max'] : null,
            isset($params['offset']) ? $params['offset'] : null
        );

        //====================================================================//
        // Load Object Fields
        $fields = $this->fields();

        //====================================================================//
        // Parse Data on Result Array
        /** @var FakeObject $object */
        foreach ($data as $object) {
            $objectData = array(
                'id' => $object->getIdentifier(),
            );

            foreach ($fields as $field) {
                if ($field['inlist']) {
                    $objectData[$field['id']] = $object->getData($field['id']);
                }
            }

            $response[] = $objectData;
        }

        //====================================================================//
        // Parse Meta Infos on Result Array
        $response['meta'] = array(
            'total' => $repository->getTypeCount($this->getSplashType(), $filter),
            'current' => \count($data),
        );

        //====================================================================//
        // Return result
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "Faker ".$this->getSplashType();
    }
}
