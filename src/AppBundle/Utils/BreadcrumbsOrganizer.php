<?php

namespace AppBundle\Utils;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use AppBundle\Entity\Collection;
use AppBundle\Entity\Album;
use AppBundle\Entity\User;
use AppBundle\Utils\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class BreadcrumbsOrganizer
{

	public function __construct(Breadcrumbs $breadcrumbs, Router $router, CurrentUser $current_user){
		$this->breadcrumbs = $breadcrumbs;
		$this->router = $router;
		$this->current_user = $current_user;
	}

	public function showHome($last=true){
		$this->breadcrumbs->addItem(
        	'menu.home', 
            ($last ? null : $this->router->generate("homepage"))
        );
	}

	public function showUser(User $user, $last=true){

		$this->showHome(false);

        $this->breadcrumbs->addItem(
        	'user.data.username', 
            ($last ? null : $this->router->generate("profile_base_default", ['username'=>$user])),
            ['%username%'=>$user->getUsername()] 
        );
	}

	public function showCollection(Collection $collection, $last=true){
		
		$user = $collection->getOwner();

		$this->showUser($user, false);

        $this->breadcrumbs->addItem(
        	'collection.data.name', 
            ($last ? null : $this->router->generate("show_collection", ['id'=>$collection->getId()])),
            ['%name%'=>$collection->getName()] 
        );

	}

	public function showAlbum(Album $album, $last=true){

		$collection = $album->getCollection();

		$this->showCollection($collection, false);

        $this->breadcrumbs->addItem(
		    'album.data.name', 
            ($last ? null : $this->router->generate('show_album', ['id'=>$album->getId()])), 
            ['%name%'=> $album->getName()]
        );
	}

	public function editAlbum(Album $album, $last=true){

		$this->showAlbum($album, false);

		$this->breadcrumbs->addItem(
			'album.title.edit_album',
			($last ? null : $this->router->generate('edit_album', ['id'=>$album->getId()]))
		);

	}

	public function permissionsAlbum(Album $album, $last=true){

		$this->showAlbum($album, false);

		$this->breadcrumbs->addItem(
			'album.title.edit_album_permissions',
			($last ? null : $this->router->generate('edit_album_permissions', ['id'=>$album->getId()]))
		);

	}

	public function uploadToAlbum(Album $album, $last=true){

		$this->showAlbum($album, false);

		$this->breadcrumbs->addItem(
			'album.title.upload_to_album',
			($last ? null : $this->router->generate('upload_to_album', ['id'=>$album->getId()]))
		);

	}

	public function listCollections($last=true){
		
		$this->showHome(false);

		$this->breadcrumbs->addItem(
			'collection.list_all',
			($last ? null : $this->router->generate('collection_index'))
		);

	}

	public function listUserAlbums($user, $last=true){

		$this->showUser($user, false);

		$this->breadcrumbs->addItem(
			'album.list_plural',
			($last ? null : $this->router->generate('user_albums', ['username'=>$user->getUsername()]))
		);

	}

	public function listUserCollections($user, $last=true){

		$this->showUser($user, false);

		$this->breadcrumbs->addItem(
			'collection.list_plural',
			($last ? null : $this->router->generate('user_collections', ['username'=>$user->getUsername()]))
		);

	}

	public function showLogin($last=true){

		$this->showHome(false);

		$this->breadcrumbs->addItem(
			'menu.login',
			($last ? null : $this->router->generate('fos_user_security_login'))
		);

	}

	public function resetPassword($last=true){

		$this->showLogin(false);

		$this->breadcrumbs->addItem(
			'user.title.reset_password',
			($last ? null : $this->router->generate('fos_user_resetting_request'))
		);

	}

	public function createAccount($last=true){

		$this->showLogin(false);

		$this->breadcrumbs->addItem(
			'user.title.create_new',
			($last ? null : $this->router->generate('fos_user_registration_register'))
		);

	}


	public function showProfile($user, $last=true){

		$this->showHome(false);

		$username = $user->getUsername();

		$this->breadcrumbs->addItem(
			'user.data.username',
			($last ? null : $this->router->generate('profile_base_default', ['username'=>$username])),
			['%username%'=>$username]
		);

	}


	public function showAccount($last=true){

		$user = $this->current_user->get();

		$this->showProfile($user, false);

		$this->breadcrumbs->addItem(
			'user.title.my_account',
			($last ? null : $this->router->generate('fos_user_profile_show'))
		);

	}


	public function editAccount($last=true){

		$this->showAccount(false);

		$this->breadcrumbs->addItem(
			'user.title.edit_account',
			($last ? null : $this->router->generate('fos_user_profile_edit'))
		);

	}


	public function changePassword($last=true){

		$this->showAccount(false);

		$this->breadcrumbs->addItem(
			'user.title.change_password',
			($last ? null : $this->router->generate('fos_user_change_password'))
		);

	}


}