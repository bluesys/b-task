<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
	/**
	 * Finds projects by a set of criteria.
	 *
	 * @param array $criteria
	 * @param array|null $orderBy
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return array workgroups.
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		$qb = $this->createQueryBuilder('p');
		$parameters = array();

		foreach ($criteria as $key => $value) {
			switch($key) {
				// Sort by id
				case 'id':
					$qb->andWhere('p.id = :id');

					$parameters['id'] = $value;
					break;

				// Sort by workgroup
				case 'workgroup':
					$qb->innerJoin('p.workgroups', 'pw');
					$qb->andWhere('pw = :workgroup_id');

					$parameters['workgroup_id'] = $value;
					break;

				// Sort by participant
				case 'participant':
					$qb->innerJoin('p.participations', 'pp');
					$qb->andWhere('pp.participant = :user_id');

					$parameters['user_id'] = $value;
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
