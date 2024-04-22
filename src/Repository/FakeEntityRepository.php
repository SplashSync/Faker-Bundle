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
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Splash\Connectors\Faker\Entity\FakeEntity;

/**
 * Splash Faker Objects Storage repository
 *
 * @template-extends  EntityRepository<FakeEntity>
 */
class FakeEntityRepository extends EntityRepository
{
    /**
     * Create Webservice Query Builder
     */
    public function createConnectorQueryBuilder(string $webserviceId, string $type): QueryBuilder
    {
        $builder = $this->createQueryBuilder('o');

        return $builder
            ->where($builder->expr()->eq("o.type", ":objectType"))
            ->andWhere($builder->expr()->eq("o.webserviceId", ":webserviceId"))
            ->setParameter('objectType', $type)
            ->setParameter("webserviceId", $webserviceId)
        ;
    }

    /**
     * Count Number of Objects of Same Type
     */
    public function getTypeCount(string $webserviceId, string $type, string $filter = null): int
    {
        $builder = $this
            ->createConnectorQueryBuilder($webserviceId, $type)
            ->select('COUNT(o.id)')
        ;

        if ($filter) {
            $builder
                ->andWhere('o.identifier = :filter')
                ->setParameter('filter', $filter)
            ;
        }

        try {
            return (int) $builder->getQuery()->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * Identify Object Using Primary Keys
     *
     * @param array<string, string> $keys Primary Keys List
     *
     * @throws Exception
     */
    public function findByPrimaryKeys(string $webserviceId, string $type, array $keys): ?FakeEntity
    {
        $builder = $this->createConnectorQueryBuilder($webserviceId, $type);
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

    /**
     * Identify Updated Objects for Changes Tracking Listener
     *
     * @return FakeEntity[]
     */
    public function getTrackedUpdated(string $webserviceId, string $type): array
    {
        $builder = $this->createConnectorQueryBuilder($webserviceId, $type);
        //====================================================================//
        // Build data Serialized Value
        $serialized = serialize(array("varchar1" => "updated"));
        $serializedString = substr($serialized, 5, strlen($serialized) - 6);
        //====================================================================//
        // Add Value to Query Builder
        $builder
            ->andWhere($builder->expr()->like("o.data", ":sequence"))
            ->setParameter("sequence", '%'.$serializedString.'%')
        ;

        // @phpstan-ignore-next-line
        return $builder->getQuery()->execute();
    }

    /**
     * Identify Deleted Objects for Changes Tracking Listener
     *
     * @return FakeEntity[]
     */
    public function getTrackedDeleted(string $webserviceId, string $type): array
    {
        $builder = $this->createConnectorQueryBuilder($webserviceId, $type);
        //====================================================================//
        // Build data Serialized Value
        $serialized = serialize(array("varchar1" => "deleted"));
        $serializedString = substr($serialized, 5, strlen($serialized) - 6);
        //====================================================================//
        // Add Value to Query Builder
        $builder
            ->andWhere($builder->expr()->like("o.data", ":sequence"))
            ->setParameter("sequence", '%'.$serializedString.'%')
        ;

        // @phpstan-ignore-next-line
        return $builder->getQuery()->execute();
    }
}
