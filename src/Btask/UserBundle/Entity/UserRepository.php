<?php

namespace Btask\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	/**
	 * Finds users by a set of criteria.
	 *
	 * @param array $criteria
	 * @param array|null $orderBy
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return array workgroups.
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		$qb = $this->createQueryBuilder('u');
		$parameters = array();

		foreach ($criteria as $key => $value) {
			switch($key) {
				// Sort by project collaborations
				case 'projectCollaboration':
					$qb->innerJoin('u.projectCollaborations', 'pc');
					$qb->andWhere('pc.project = :project_id');

					$parameters['project_id'] = $value;
					break;

				default:
					throw new \InvalidArgumentException('parameter not available');
				break;
			}
		}

		$qb->setParameters($parameters);

		($offset) ? $qb->setFirstResult($offset) : null;
		($limit) ? $qb->setMaxResults($limit) : null;

		return $qb->getQuery()->getArrayResult();
	}
}
