<?php

// src/AppBundle/Repository/AlbumRepository.php

namespace AppBundle\Repository;

use AppBundle\Entity\Album_Photo;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
// use Pagerfanta\Pagerfanta;


class Album_PhotoRepository extends EntityRepository
{


    /**
     * Return all albums that are owned by the specified $owner and are either public or the current $user is the owner
     * TODO: Add albums that the current user can see through permissions
     *
     * @param Album $owner 
     *
     * @return int
     */
    public function getMaxPosition($album)
    {

        $qb = $this->createQueryBuilder('a');

        $qb 
            ->where($qb->expr()->andX(
                $qb->expr()->eq('a.album', ':album')
            ))
            ->setParameter('album', $album->getId())
            ->orderBy('a.position','DESC')
            ->setMaxResults(1)
        ;

        try{
            return $qb->getQuery()->getSingleResult()->getPosition();
        }catch(\Doctrine\ORM\NoResultException $e){
            return 0; // If no results, the max. position is 0
        }

    }



}
