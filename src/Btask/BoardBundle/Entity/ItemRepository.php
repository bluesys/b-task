<?php

namespace Btask\BoardBundle\Entity;

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
     * @param datetime $date
     * @return collection tasks.
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
                            throw new \InvalidArgumentException('state parameter not available');
                            break;
                    }
                    break;

                // Select tasks by their executor
                case 'executor':
                    $qb->andWhere('i.executor = :executor_id');
                    $parameters['executor_id'] = $value;

                    break;

                // Select tasks by project
                case 'project':
                    $qb->innerJoin('i.project', 'ip');
                    $qb->andWhere('i.project = :project_id');
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

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds notes by a set of criteria
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param datetime $date
     * @return collection notes.
     */
    public function findNotesBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, $date = null)
    {

        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.type', 'it');
        $qb->andWhere('it.name = :type');
        $parameters = array('type' => 'Note');

        foreach ($criteria as $key => $value) {
            switch ($key) {
                // Select notes by project
                case 'project':
                    $qb->innerJoin('i.project', 'ip');
                    $qb->andWhere('i.project = :project_id');
                    $parameters['project_id'] = $value;

                    break;

                // Select notes by user
                case 'user':
                    $qb->innerJoin('i.project', 'ip');
                    $qb->innerJoin('ip.collaborations', 'ipc');
                    $qb->andWhere('ipc.participant = :user_id');
                    $parameters['user_id'] = $value;

                    break;

                // Sort by owner
                case 'owner':
                    $qb->andWhere('i.owner = :owner');
                    $parameters['owner'] = $value;

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


    /**
     * Finds post-it by a set of criteria
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param datetime $date
     * @return collection post-it.
     */
    public function findPostItBy(array $criteria, $limit = null, $offset = null, $date = null)
    {

        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.type', 'it');
        $qb->andWhere('it.name = :type');
        $parameters = array('type' => 'Post-it');

        foreach ($criteria as $key => $value) {
            switch ($key) {
                // Sort by owner
                case 'owner':
                    $qb->andWhere('i.owner = :owner');
                    $parameters['owner'] = $value;

                    break;

                // Sort by opened status
                case 'status':
                    $qb->andWhere('i.status = :true');
                    $parameters['true'] = $value;

                    break;

                default:
                    throw new \InvalidArgumentException('parameter not available');
                    break;
            }
        }

        $qb->setParameters($parameters);

        ($offset) ? $qb->setFirstResult($offset) : null;
        ($limit) ? $qb->setMaxResults($limit) : null;

        $qb->orderBy('i.created', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds one post-it
     *
     * @param array $criteria
     * @return object post-it.
     */
    public function findOnePostItBy(array $criteria) {
        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.type', 'it');
        $qb->andWhere('it.name = :type');
        $parameters = array('type' => 'Post-it');

        foreach ($criteria as $key => $value) {
            switch ($key) {
                // Sort by id
                case 'id':
                    $qb->andWhere('i.id = :id');
                    $parameters['id'] = $value;

                    break;

                default:
                    throw new \InvalidArgumentException('parameter not available');
                    break;
            }

        }

        $qb->setParameters($parameters);

        try {
            return $qb->getQuery()->getSingleResult();
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }


    /**
     * Finds one task
     *
     * @param array $criteria
     * @return object task.
     */
    public function findOneTaskBy(array $criteria) {
        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.type', 'it');
        $qb->andWhere('it.name = :type');
        $parameters = array('type' => 'Task');

        foreach ($criteria as $key => $value) {
            switch ($key) {
                // Sort by id
                case 'id':
                    $qb->andWhere('i.id = :id');
                    $parameters['id'] = $value;

                    break;

                default:
                    throw new \InvalidArgumentException('parameter not available');
                    break;
            }

        }

        $qb->setParameters($parameters);

        try {
            return $qb->getQuery()->getSingleResult();
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }


    /**
     * Finds one note
     *
     * @param array $criteria
     * @return object note.
     */
    public function findOneNoteBy(array $criteria) {
        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.type', 'it');
        $qb->andWhere('it.name = :type');
        $parameters = array('type' => 'Note');

        foreach ($criteria as $key => $value) {
            switch ($key) {
                // Sort by id
                case 'id':
                    $qb->andWhere('i.id = :id');
                    $parameters['id'] = $value;

                    break;

                default:
                    throw new \InvalidArgumentException('parameter not available');
                    break;
            }

        }

        $qb->setParameters($parameters);

        try {
            return $qb->getQuery()->getSingleResult();
        }
        catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
