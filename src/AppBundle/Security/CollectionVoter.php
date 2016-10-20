<?php

// src/AppBundle/Security/CollectionVoter.php
namespace AppBundle\Security;

use AppBundle\Entity\Collection;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CollectionVoter extends ItemVoter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const EDIT_PERM = 'edit_permissions';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::EDIT_PERM))) {
            return false;
        }

        // only vote on Collection objects inside this voter
        if (!$subject instanceof Collection) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // Not using this condition for Collection
        // if (!$user instanceof User) {
        //     // the user must be logged in; if not, deny access
        //     return false;
        // }

        // you know $subject is a Collection object, thanks to supports
        /** @var Collection $collection */
        $collection = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($collection, ($user instanceof User) ? $user : NULL);
            case self::EDIT:
                return $this->canEdit($collection, ($user instanceof User) ? $user : NULL);
            case self::EDIT_PERM:
                return $this->canEditPermissions($collection, ($user instanceof User) ? $user : NULL);
        }

        throw new \LogicException('This code should not be reached!');
    }

}