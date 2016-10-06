<?php

// src/AppBundle/Repository/CollectionRepository.php

namespace AppBundle\Repository;

use AppBundle\Entity\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;


class CollectionRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function queryLatest()
    {

        $qb = $this->createQueryBuilder('c');

        return $qb
            ->where(
                $qb->expr()->eq('c.public', '1')
            )
            ->orderBy('c.createDate','DESC')
            
            // 'WHERE p.publishedAt <= :now'
            // ->setParameter('now', new \DateTime())
        ;
    }

    /**
     * Return all collections that are owned by the specified $owner and are either public or the current $user is the owner
     * TODO: Add collections that the current user can see through permissions
     *
     * @param User $owner 
     * @param User $user 
     *
     * @return Query
     */
    public function queryByOwner($owner, $user=NULL)
    {

        $qb = $this->createQueryBuilder('c');

        return $qb 
            ->where($qb->expr()->andX(
                $qb->expr()->eq('c.owner', ':owner'),
                $qb->expr()->orX(
                    $qb->expr()->eq('c.public', '1'),
                    $qb->expr()->eq('c.owner', ($user===NULL ? '0' : $user->getId())) // If user is not set, use 0 to effectively remove this condition
                )
            ))
            ->setParameter('owner', $owner->getId())

            ->orderBy('c.createDate','DESC')
            
            // 'WHERE p.publishedAt <= :now'
            // ->setParameter('now', new \DateTime())
        ;
    }

    

    /**
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findLatest($page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryLatest(), false));
        $paginator->setMaxPerPage(Collection::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Return all collections that are owned by the specified $owner and are either public or the current $user is the owner
     * 
     * @param User $owner
     * @param User $user
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findByOwner($owner, $user = NULL, $page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryByOwner($owner, $user), false));
        $paginator->setMaxPerPage(Collection::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

}
