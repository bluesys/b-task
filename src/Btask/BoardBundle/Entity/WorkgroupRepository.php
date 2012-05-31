<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Btask\UserBundle\Entity\User;

class WorkgroupRepository extends EntityRepository
{
	/**
	 * Finds workgroups by a set of criteria.
	 *
	 * @param array $criteria
	 * @param array|null $orderBy
	 * @param int|null $limit
	 * @param int|null $offset
	 * @return array workgroups.
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		$qb = $this->createQueryBuilder('w');
		$parameters = array();
		$singleResult = false;

		foreach ($criteria as $key => $value) {
			switch($key) {
				// Sort by id
				case 'id':
					$qb->andWhere('w.id = :id');

					$parameters['id'] = $value;
					$singleResult = true;
					break;

				// Sort by slug
				case 'slug':
					$qb->andWhere('w.slug = :slug');

					$parameters['slug'] = $value;
					$singleResult = true;
					break;

				// Sort by participant
				case 'participant':
					$qb->innerJoin('w.participations', 'wp');
					$qb->andWhere('wp.participant = :user_id');

					$parameters['user_id'] = $value;
					break;

				// Sort by participant
				case 'shared':
					$qb->andWhere('wp.shared = :true');

					$parameters['true'] = $value;
					break;

				// Sort by owner
				case 'owner':
					$qb->innerJoin('w.participations', 'wp');
					$qb->andWhere('wp.participant = :user_id');
					$qb->andWhere('wp.owner = :true');

					$parameters['user_id'] = $value;
					$parameters['true'] = true;
					break;

				default:
					throw new \InvalidArgumentException('parameter not available');
					break;
			}
		}

		$qb->setParameters($parameters);

		($offset) ? $qb->setFirstResult($offset) : null;
		($limit) ? $qb->setMaxResults($limit) : null;

		// if there is an id or an slug parameter in the request, return only one result
		if ($singleResult) {
			return $qb->getQuery()->getSingleResult();
		}

		return $qb->getQuery()->getArrayResult();

	}
}
