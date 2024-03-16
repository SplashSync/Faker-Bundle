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

namespace Splash\Connectors\Faker\Repository;

use Doctrine\ORM\EntityRepository;
use Exception;
use Splash\Connectors\Faker\Entity\FakeEntity;

/**
 * Splash Faker Objects Storage repository
 *
 * @extends  EntityRepository<FakeEntity>
 */
class FakeEntityRepository extends EntityRepository
{
    /**
     * Count Number of Objects of Same Type
     *
     * @param string      $type
     * @param null|string $filter
     *
     * @throws Exception
     *
     * @return int
     */
    public function getTypeCount(string $type, string $filter = null): int
    {
        $builder = $this->createQueryBuilder('o');

        $builder
            ->select('COUNT(o.id)')
            ->where('o.type = :type')
            ->setParameter('type', $type)
        ;

        if ($filter) {
            $builder
                ->andWhere('o.identifier = :filter')
                ->setParameter('filter', $filter)
            ;
        }

        // @phpstan-ignore-next-line
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * Identify Object Using Primary Keys
     *
     * @param string                $webserviceId
     * @param string                $type
     * @param array<string, string> $keys         Primary Keys List
     *
     * @throws Exception
     *
     * @return null|FakeEntity
     */
    public function findByPrimaryKeys(string $webserviceId, string $type, array $keys): ?FakeEntity
    {
        $builder = $this->createQueryBuilder('o');
        //====================================================================//
        // Filter on Object Type
        $builder
            ->where($builder->expr()->eq("o.type", ":objectType"))
            ->andWhere($builder->expr()->eq("o.webserviceId", ":webserviceId"))
            ->setParameter(":objectType", $type)
            ->setParameter(":webserviceId", $webserviceId)
        ;
        //====================================================================//
        // Walk on Primary Keys
        foreach ($keys as $name => $value) {
            //====================================================================//
            // Build data Serialized Value
            $serialized = serialize(array($name => $value));
            $serializedString = substr($serialized, 5, strlen($serialized) - 6);
            //====================================================================//
            // Add Value to Query Builder
            $builder
                ->andWhere($builder->expr()->like("o.data", ":".$name))
                ->setParameter(":".$name, '%'.$serializedString.'%')
            ;
        }

        // @phpstan-ignore-next-line
        return $builder->getQuery()->getOneOrNullResult();
    }
}
