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

namespace Splash\Connectors\FakerBundle\Repository;

/**
 * @abstract    Splash Faker Objects Storage repository
 */
class FakeObjectRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @abstract    Count Number of Objects of Same Type
     *
     * @param string $type
     * @param string $filter
     *
     * @return int
     */
    public function getTypeCount($type, $filter = null)
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
                ->setParameter('filter', $filter);
        }

        return $builder->getQuery()->getSingleScalarResult();
    }
}
