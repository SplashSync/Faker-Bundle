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

namespace Splash\Connectors\Faker\Objects;

use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use Splash\Bundle\Models\AbstractStandaloneObject;
use Splash\Client\Splash;
use Splash\Connectors\Faker\Entity\FakeEntity;
use Splash\Connectors\Faker\Repository\FakeEntityRepository;
use Splash\Connectors\Faker\Services\FieldsBuilder;
use Splash\Models\Objects\IntelParserTrait;
use Splash\Models\Objects\ListsTrait;
use Splash\Models\Objects\PrimaryKeysAwareInterface;
use Splash\Models\Objects\SimpleFieldsTrait;

/**
 * Generic Faker Object.
 */
class Generic extends AbstractStandaloneObject implements PrimaryKeysAwareInterface
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
     * {@inheritdoc}
     */
    protected static string $description = 'Faker Object';

    /**
     * {@inheritdoc}
     */
    protected static string $ico = 'fa fa-magic';

    //====================================================================//
    // Private variables
    //====================================================================//

    /**
     * @var FakeEntity
     */
    protected FakeEntity $entity;

    /**
     * @phpstan-var ArrayObject
     */
    protected object $object;

    //====================================================================//
    // Service Constructor
    //====================================================================//

    /**
     * Service Constructor
     */
    public function __construct(
        private FieldsBuilder $fieldBuilder,
        private EntityManagerInterface $entityManager
    ) {
    }

    //====================================================================//
    // Generic Objects Functions (See Splash PhpCore IntelliParser)
    //====================================================================//

    /**
     * Build Core Fields using FieldFactory
     *
     * @return void
     */
    public function buildCoreFields(): void
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
    public function getCoreFields(string $key, string $fieldName): void
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
    public function setCoreFields(string $fieldName, $data): void
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
    public function objectsList($filter = null, $params = null): array
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();

        $response = array();
        /** @var FakeEntityRepository $repository */
        $repository = $this->entityManager->getRepository(FakeEntity::class);

        //====================================================================//
        // Prepare List Filters List
        $search = array(
            'webserviceId' => $this->getWebserviceId(),
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
            $params['max'] ?? null,
            $params['offset'] ?? null
        );

        //====================================================================//
        // Load Object Fields
        $fields = $this->fields();

        //====================================================================//
        // Parse Data on Result Array
        /** @var FakeEntity $object */
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
            'total' => $repository->getTypeCount($this->getWebserviceId(), $this->getSplashType(), $filter),
            'current' => \count($data),
        );

        //====================================================================//
        // Return result
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getByPrimary(array $keys): ?string
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        /** @var FakeEntityRepository $repository */
        $repository = $this->entityManager->getRepository(FakeEntity::class);

        //====================================================================//
        // Get Repository
        try {
            $object = $repository->findByPrimaryKeys(
                $this->getWebserviceId(),
                $this->getSplashType(),
                $keys
            );
        } catch (\Exception $exception) {
            Splash::log()->report($exception);

            return null;
        }

        return $object ? $object->getIdentifier() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return "Faker ".$this->getSplashType();
    }
}
