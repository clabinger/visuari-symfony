<?php

// src/AppBundle/Security/AlbumVoter.php
namespace AppBundle\Security;

use AppBundle\Entity\Album;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AlbumVoter extends ItemVoter
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

        // only vote on Album objects inside this voter
        if (!$subject instanceof Album) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // Not using this condition for Album
        // if (!$user instanceof User) {
        //     // the user must be logged in; if not, deny access
        //     return false;
        // }

        // you know $subject is a Album object, thanks to supports
        /** @var Album $album */
        $album = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($album, ($user instanceof User) ? $user : NULL);
            case self::EDIT:
                return $this->canEdit($album, ($user instanceof User) ? $user : NULL);
            case self::EDIT_PERM:
                return $this->canEditPermissions($album, ($user instanceof User) ? $user : NULL);
        }

        throw new \LogicException('This code should not be reached!');
    }


}