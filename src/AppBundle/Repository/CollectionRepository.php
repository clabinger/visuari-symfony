<?php

// src/AppBundle/Repository/CollectionRepository.php

namespace AppBundle\Repository;

use AppBundle\Entity\Collection;
use AppBundle\Entity\User;

use AppBundle\Security\PermissionChecker;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;


class CollectionRepository extends EntityRepository
{
    /**
     *
     * Return all collections that the current $user has permission to see
     *
     * @param User $user
     *
     * @return Query
     */
    public function queryLatest(User $user = null)
    {
        $permissionChecker = new PermissionChecker();

        return $permissionChecker->queryItems($this, $user, 'collection');
    }

    /**
     * Return all collections owned by the specified $owner that the current user has permission to see
     *
     * @param User $owner 
     * @param User $user 
     *
     * @return Query
     */
    public function queryByOwner(User $owner, User $user=NULL)
    {

        $permissionChecker = new PermissionChecker();

        $additional_expr = Array('eq', 'i.owner', ':owner');

        $additional_param = Array(
            'owner'=>$owner->getId()
        );

        return $permissionChecker->queryItems($this, $user, 'collection', $additional_expr, $additional_param);
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
        $paginator->setMaxPerPage(Collection::NUM_ITEMS);
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
        $paginator->setMaxPerPage(Collection::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

}
