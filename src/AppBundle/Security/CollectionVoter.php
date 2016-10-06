<?php

// src/AppBundle/Security/CollectionVoter.php
namespace AppBundle\Security;

use AppBundle\Entity\Collection;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CollectionVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
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

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Collection object, thanks to supports
        /** @var Collection $collection */
        $collection = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($collection, $user);
            case self::EDIT:
                return $this->canEdit($collection, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Collection $collection, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($collection, $user)) {
            return true;
        }

        return $collection->isPublic();
    }

    private function canEdit(Collection $collection, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $collection->getOwner();
    }
}