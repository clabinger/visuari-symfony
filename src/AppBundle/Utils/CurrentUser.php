<?php

namespace AppBundle\Utils;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;


class CurrentUser
{

	public function __construct(TokenStorage $security_context, AuthorizationChecker $authorization_checker){
		$this->security_context = $security_context;
		$this->authorization_checker = $authorization_checker;
	}

	public function get(){


        if(!$this->security_context->getToken()){
            return null;
        }


    	if(
    		$this->authorization_checker->isGranted('IS_AUTHENTICATED_FULLY')
    			||
    		$this->authorization_checker->isGranted('IS_AUTHENTICATED_REMEMBERED')
    	){
        	return $this->security_context->getToken()->getUser();
        }else{
        	return null;
        }

	}

}