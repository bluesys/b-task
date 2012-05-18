<?php

namespace Btask\DashboardBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{

    /**
     * Find items by their type
     *
     * @param string $itemType
     */
    public function findByItemType($itemType = null)
    {

		$qb = $this->createQueryBuilder('i');

		if ($itemType) {
        	$qb->innerJoin('i.type', 'it')
           		->andWhere('it.name = :type')
           		->setParameter('type', $itemType);
        }

        $qb->orderBy('i.created', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }
}