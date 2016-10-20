<?php

// src/AppBundle/Menu/Builder.php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization;
use AppBundle\Utils\CurrentUser;

class Builder
{

    public function __construct(FactoryInterface $factory, CurrentUser $user)
    {
        $this->current_user = $user;
        $this->factory = $factory;
    }

    public function mainMenu(array $options)
    {

        $user = $this->current_user->get();


        $menu = $this->factory->createItem('root', array(
            'childrenAttributes'    => array(
                'class'             => 'menu dropdown align-right',
                'data-dropdown-menu' => '',
            ),
        ));

        if(!$user){

            $menu->addChild('menu.login', array('route' => 'fos_user_security_login'));
            
        }else{
            $username = $user->getUsername();

            $profile_link = array(
                'route' => 'profile_base_default', 
                'routeParameters'=>array('username'=>$username)
            );

            $profile = $menu->addChild($username, Array('uri'=>'#'))->setExtra('translation_domain', false);

            $profile->setChildrenAttribute('class', 'menu');

            $profile->addChild('menu.my_profile', $profile_link);

            $profile->addChild('menu.logout', array('route' => 'fos_user_security_logout'));

        }

        $menu->addChild('menu.collections', array('route' => 'collection_index'));
        $menu->addChild('menu.home', array('route' => 'homepage'));

        return $menu;
    }
}