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
     * Get the max "position" field that exists in the Album_Photo entity for a particular album. 
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
