<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Btask\UserBundle\Entity\User;

class WorkgroupRepository extends EntityRepository
{
	public function findByUser(User $user) {
		$qb = $this->createQueryBuilder('w');
    	$qb->innerJoin('w.usersWorkgroups', 'uw')
       			->andWhere('uw.user = :user_id')
       			->setParameter('user_id', $user->getId());

        $qb->orderBy('w.created', 'DESC');

        return $qb->getQuery()->getArrayResult();
	}
}
