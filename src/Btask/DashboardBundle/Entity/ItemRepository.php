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
     * Find tasks by their status
     *
     * @param string $status
     * @param datetime $date
     */
    public function findTasksByStatus($status, $date = null)
    {
        $today = new \Datetime();

        // Set today date if no date was passed
        if (!$date) {
           $date = $today;
        }

        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.type', 'it');
        $qb->andWhere('it.name = :type');

        $status = strtolower($status);

        switch ($status) {

            case 'overdue':
                $qb->andWhere('i.status = :open');
                $qb->andWhere('i.due < :today');
                $qb->setParameters(array(
                    'type' => 'Task',
                    'today' => $today,
                    'open' => true,
                ));

                break;

            case 'todo':
                $qb->andWhere('i.status = :open');
                $qb->andWhere('i.planned = :today');
                $qb->setParameters(array(
                    'type' => 'Task',
                    'today' => $today,
                    'open' => true,
                ));

                break;

            case 'done':
                $qb->andWhere('i.status = :closed');
                $qb->andWhere('i.planned = :today');
                $qb->setParameters(array(
                    'type' => 'Task',
                    'today' => $today,
                    'closed' => false,
                ));

                break;

            default:
                throw new \Exception('Status parameter not available');
        }

        $qb->orderBy('i.created', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }
}