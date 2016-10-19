<?php
// src/AppBundle/Form/DataTransformer/UserToUsernameTransformer.php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToUsernameTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (user) to a string (username).
     *
     * @param  User|null $user
     * @return string
     */
    public function transform($user)
    {
        if (null === $user) {
            return '';
        }

        return $user->getUsername();
    }

    /**
     * Transforms a string (username) to an object (user).
     *
     * @param  string $username
     * @return User|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($username)
    {
        if (!$username) {
             throw new TransformationFailedException('Username was not provided.');
        }

        $user = $this->manager
            ->getRepository('AppBundle:User')
            // query for the user with this username
            ->findOneByUsername($username)
        ;

        if (null === $user) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'Username "%s" was not found.',
                $username
            ));
        }

        return $user;
    }
}