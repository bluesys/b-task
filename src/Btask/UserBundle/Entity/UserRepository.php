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
	 * @return array users.
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		$qb = $this->createQueryBuilder('u');
		$parameters = array();

		foreach ($criteria as $key => $value) {
			switch($key) {
				// Sort by project collaborations
				case 'project':
					$qb->innerJoin('u.collaborations', 'uc');
					$qb->andWhere('uc.project = :project_id');

					$parameters['project_id'] = $value;
					break;

				case 'usernameCanonical':
					$qb->andWhere('u.usernameCanonical = :usernameCanonical');

					$parameters['usernameCanonical'] = $value;
					break;

				case 'emailCanonical':
					$qb->andWhere('u.emailCanonical = :emailCanonical');

					$parameters['emailCanonical'] = $value;
					break;

				default:
					throw new \InvalidArgumentException('parameter not available');
				break;
			}
		}

		$qb->setParameters($parameters);

		($offset) ? $qb->setFirstResult($offset) : null;
		($limit) ? $qb->setMaxResults($limit) : null;

		return $qb->getQuery()->getResult();
	}
}
