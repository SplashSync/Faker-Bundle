<?php

namespace Splash\Connectors\FakerBundle\Repository;

class FakeObjectRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function getTypeCount($type, $filter = null)
    {
        $Builder = $this->createQueryBuilder("o");
        
        $Builder
            ->select('COUNT(o.id)')
            ->where('o.type = :type')
            ->setParameter('type', $type)
            ;
        
        if ($filter) {
            $Builder
              ->where('identifier = :filter')
              ->setParameter('filter', $filter);
        }

        return $Builder->getQuery()->getSingleScalarResult();
    }
}
