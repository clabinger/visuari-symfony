<?php

// src/AppBundle/Repository/AlbumRepository.php

namespace AppBundle\Repository;

use AppBundle\Entity\Collection;
use AppBundle\Entity\Album;
use AppBundle\Entity\User;
use AppBundle\Entity\Permission;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

use AppBundle\Security\PermissionChecker;

class AlbumRepository extends EntityRepository
{
    /**
     * Return all albums that the current $user has permission to see
     * 
     * @param User $user
     *
     * @return Query
     */
    public function queryLatest(User $user = null)
    {
        $permissionChecker = new PermissionChecker();

        return $permissionChecker->queryItems($this, $user, 'album');        
    }



    /**
     * Return all albums in the specified $collection that the current $user has permission to see
     *
     * @param Collection $collection
     * @param User $user 
     *
     * @return Query
     */
    public function queryByCollection(Collection $collection, $user=null)
    {
        $permissionChecker = new PermissionChecker();

        $additional_expr = Array('eq', 'i.collection', ':collection');

        $additional_param = Array(
            'collection'=>$collection->getId()
        );

        return $permissionChecker->queryItems($this, $user, 'album', $additional_expr, $additional_param);
    }



    /**
     * Return all albums owned by the specified $owner that the current $user has permission to see
     *
     * @param User $owner 
     * @param User $user 
     *
     * @return Query
     */
    public function queryByOwner(User $owner, User $user=null)
    {
        $permissionChecker = new PermissionChecker();

        $additional_expr = Array('eq', 'i.owner', ':owner');

        $additional_param = Array(
            'owner'=>$owner->getId()
        );

        return $permissionChecker->queryItems($this, $user, 'album', $additional_expr, $additional_param);
    }

    

    /**
     * @param User $user
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findLatest(User $user = null, $page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryLatest($user), false));
        $paginator->setMaxPerPage(Album::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @param Collection $collection
     * @param User $user
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findByCollection(Collection $collection, User $user = null, $page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryByCollection($collection, $user), false));
        $paginator->setMaxPerPage(Album::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * 
     * @param User $owner
     * @param User $user
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findByOwner(User $owner, User $user = null, $page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryByOwner($owner, $user), false));
        $paginator->setMaxPerPage(Album::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

}
