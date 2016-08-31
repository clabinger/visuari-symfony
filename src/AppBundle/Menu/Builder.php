<?php

// src/AppBundle/Menu/Builder.php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authorization;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $menu = $factory->createItem('root', array(
            'childrenAttributes'    => array(
                'class'             => 'menu dropdown',
                'data-dropdown-menu' => '',
            ),
        ));

        $menu->addChild('menu.home', array('route' => 'homepage'));
        $menu->addChild('menu.upload', array('route' => 'upload'));
        
        if(is_string($user)){

            $menu->addChild('menu.login', array('route' => 'fos_user_security_login'));
            
        }else{
            $username = $user->getUsername();

            $profile_link = array(
                'route' => 'profile_base_default', 
                'routeParameters'=>array('username'=>$username)
            );

            $profile = $menu->addChild($username, $profile_link)->setExtra('translation_domain', false);

            $profile->setChildrenAttribute('class', 'menu');

            $profile->addChild('menu.my_profile', $profile_link);

            $profile->addChild('menu.logout', array('route' => 'fos_user_security_logout'));

        }


        return $menu;
    }
}