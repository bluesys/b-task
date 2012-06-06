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
	 * @return array projects.
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		$qb = $this->createQueryBuilder('p');
		$parameters = array();

		foreach ($criteria as $key => $value) {
			switch($key) {
				// Sort by workgroup
				case 'workgroup':
					$qb->innerJoin('p.collaborations', 'pc');

					if($value) {
						$qb->andWhere('pc.workgroup = :workgroup_id');
						$parameters['workgroup_id'] = $value;
					}
					else {
						$qb->andWhere('pc.workgroup is NULL');
					}

					break;

				// Sort by slug
				case 'slug':
					$qb->andWhere('p.slug = :project_slug');
					$parameters['project_slug'] = $value;

					break;

				// Sort by slug
				case 'user':
					$qb->innerJoin('p.collaborations', 'pco');
					$qb->andWhere('pco.participant = :participant_id');

					$parameters['participant_id'] = $value;

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
