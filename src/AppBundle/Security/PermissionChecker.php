<?php

namespace AppBundle\Security;

// Assist Repositories in constructing queries that check the standard Permission structure for Collection/Album/Album_Photo entities.

class PermissionChecker
{

	// public function __construct(EntityManager $em){
	// 	$this->$em = $em;
	// }

	private function build_expr($qb, $array){
		
		$type = $array[0]; // expr method - andX, orX, eq, etc.
		// $array[1...] - arguments for expr method

		for($i=1; $i<count($array); $i++){
			if(is_array($array[$i])){
				$items[] = build_expr($qb, $array[$i]);
			}else{
				$items[] = $array[$i];
			}
		}

		return call_user_func_array(array($qb->expr(), $type), $items);

	}

	/**
     * Since the 3 entities Album, Album_Photo, Collection all interact with the Permission Entity in the same way, abstract the join query here. 
     * $entity should be one of 'album', 'album_photo', 'collection'
     * $additional_expr = 
	 *     Array('andX', 
	 *	       Array(
	 *				'orX',
	 *              Array('eq',
     *                  'field'
     *                  'value'
     *              )
     *              Array('gt',
     *                  'field'
     *                  'number'
     *              )
     *         )
	 *     )
     *
     * @param EntityRepository $repository 
     * @param User $user
     * @param String $entity 
     * @param Array $additional_expr 
     * @param Array $additional_param
     *
     * @return QueryBuilder
     */
	public function queryItems($repository, $user, $entity, $additional_expr=null, $additional_param=null){

        if($user instanceof \AppBundle\Entity\User){
            $user = $user->getId();
        }else{
            $user = 0; // No users have id=0, so either another condition will match and show the record, or that record will not be shown, effectively not doing this check.
        }

        $qb = $repository->createQueryBuilder('i');

        // If we are getting a list of albums, inherit permissions that are for the collection that owns each album.
        if($entity==='album'){ // pi = permission_inherited
           $qb->leftJoin('AppBundle\Entity\Permission', 'pi', 'WITH', 'pi.collection = i.collection');
        }

		$qb
            ->leftJoin('AppBundle\Entity\Permission', 'p', 'WITH', 'p.'.$entity.' = i.id')
            ->where($qb->expr()->andX(
                $additional_expr ? $this->build_expr($qb, $additional_expr) : null,
                $qb->expr()->orX(
                    $qb->expr()->andX( // Direct access
                        $qb->expr()->eq('p.grantee', ':user'), // The current user is the grantee ...
                        $qb->expr()->gte('p.level', 1) // ... and they have been given view access or higher
                    ),
                    $qb->expr()->andX( // Inherited access
                        $qb->expr()->eq('pi.grantee', ':user'), // The current user is the grantee ...
                        $qb->expr()->gte('pi.level', 1), // ... and they have been given view access or higher ...
                        $qb->expr()->orX( 
                            $qb->expr()->neq('p.level', 0), // ... as long as access has not been explicitly denied directly on the item
                            $qb->expr()->isNull('p.level')
                        )
                    ),
                    $qb->expr()->eq('i.public', '1'), // Or item is public
                    $qb->expr()->eq('i.owner', $user) // Or current user owns the item
                )
            ))
            ->setParameter('user', $user)

            ->orderBy('i.createDate','DESC')
        ;

        if(is_array($additional_param)){
	        foreach($additional_param as $key=>$value){
	        	$qb->setParameter($key, $value);
	        }
		}

        return $qb;

	}


}