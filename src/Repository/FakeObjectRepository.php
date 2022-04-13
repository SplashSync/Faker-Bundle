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

/**
 * Splash Faker Objects Storage repository
 */
class FakeObjectRepository extends EntityRepository
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
                ->where('identifier = :filter')
                ->setParameter('filter', $filter)
            ;
        }
        // @phpstan-ignore-next-line
        return $builder->getQuery()->getSingleScalarResult();
    }
}
