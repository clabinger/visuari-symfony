<?php

// src/AppBundle/Repository/AlbumRepository.php

namespace AppBundle\Repository;

use AppBundle\Entity\Album;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;


class AlbumRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function queryLatest()
    {

        $qb = $this->createQueryBuilder('a');

        return $qb
            ->where(
                $qb->expr()->eq('a.public', '1')
            )
            ->orderBy('a.createDate','DESC')
            
            // 'WHERE p.publishedAt <= :now'
            // ->setParameter('now', new \DateTime())
        ;
    }

    /**
     * Return all albums that are owned by the specified $owner and are either public or the current $user is the owner
     * TODO: Add albums that the current user can see through permissions
     *
     * @param User $owner 
     * @param User $user 
     *
     * @return Query
     */
    public function queryByOwner($owner, $user=NULL)
    {

        $qb = $this->createQueryBuilder('a');

        return $qb 
            ->where($qb->expr()->andX(
                $qb->expr()->eq('a.owner', ':owner'),
                $qb->expr()->orX(
                    $qb->expr()->eq('a.public', '1'),
                    $qb->expr()->eq('a.owner', ($user===NULL ? '0' : ':user')) // If user is not set, use 0 to effectively remove this condition
                )
            ))
            ->setParameter('owner', $owner->getId())
            ->setParameter('user', $user->getId())

            ->orderBy('a.createDate','DESC')
            
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
        $paginator->setMaxPerPage(Album::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Return all albums that are owned by the specified $owner and are either public or the current $user is the owner
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
        $paginator->setMaxPerPage(Album::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

}
