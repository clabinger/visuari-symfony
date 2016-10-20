<?php

// src/AppBundle/Security/ItemVoter.php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Entity\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Doctrine\Common\Collections\Criteria;

abstract class ItemVoter extends Voter
{
    
    private function getLevel($item, User $user=NULL){

        if($user===null){
            return null;
        }

        $qb = new Criteria();

        $criteria = $qb->create()->where($qb->expr()->andX(
            $qb->expr()->eq("grantee", $user)
        ));

        $result = $item->getPermissions()->matching($criteria)->get(0);

        if($result instanceof Permission){
            return $result->getLevel();
        }else{
            return null;
        }

    }

    protected function canView($item, User $user=NULL)
    {
        // if they can edit, they can view
        if ($this->canEdit($item, $user)) {
            return true;
        }else if($this->getLevel($item, $user)===1){
            return true;
        }

        return $item->isPublic();
    }

    protected function canEdit($item, User $user=NULL)
    {
            
        if(in_array($this->getLevel($item, $user), [2,3])){
            return true;
        }

        return $user === $item->getOwner();
    }

    protected function canEditPermissions($item, User $user=NULL)
    {
            
        if(in_array($this->getLevel($item, $user), [3])){
            return true;
        }        

        return $user === $item->getOwner();
    }



}