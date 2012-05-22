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

    /**
     * Finds tasks by a set of criteria
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array $tasks.
     */
    public function findTasksBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, $date = null)
    {

        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.type', 'it');
        $qb->andWhere('it.name = :type');
        $parameters = array('type' => 'Task');

        // Set today date if no date was passed
        $today = new \Datetime('now');
        if (!$date) {
           $date = $today;
        }

        foreach ($criteria as $key => $value) {
            switch ($key) {

                // Select tasks by their state (Overdue, Planned or Done)
                case 'state':

                    switch ($value) {
                        case 'overdue':
                            $qb->andWhere('i.status = :open');
                            $qb->andWhere('i.due < :today');

                            $parameters['open'] =  true;
                            $parameters['today'] = $date->format('Y-m-d');

                            break;

                        case 'planned':
                            $qb->andWhere('i.status = :open');
                            $qb->andWhere('i.planned = :today');

                            $parameters['open'] =  true;
                            $parameters['today'] = $date->format('Y-m-d');

                            break;

                        case 'done':
                            $qb->andWhere('i.status = :close');
                            $qb->andWhere('i.planned = :today');

                            $parameters['close'] =  false;
                            $parameters['today'] = $date->format('Y-m-d');

                            break;

                        default:
                            throw new \Exception('State parameter not available');
                    }
                    break;

                // Select tasks by their executor
                case 'executor':

                    // Check if the parameter is an user
                    if (!(is_object($value)) && !($value instanceof \Btask\UserBundle\Entity\User)) {
                        throw new \Exception('Executor parameter not available, need to be an instance of \Btask\UserBundle\Entity\User');
                    }

                    $qb->andWhere('i.executor = :executor');
                    $parameters['executor'] = $value;

                    break;
            }
        }

        $qb->setParameters($parameters);

        ($offset) ? $qb->setFirstResult($offset) : null;
        ($limit) ? $qb->setMaxResults($limit) : null;

        return $qb->getQuery()->getArrayResult();
    }
}
