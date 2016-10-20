<?php

// src/AppBundle/Controller/ProfileController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\User;
use AppBundle\Entity\Collection;
use AppBundle\Entity\Album;

/**
 * Controller used to manage collections
 *
 * @Route("/user/{username}", defaults={"username": "test"})
 *
 */
class ProfileController extends Controller
{

	private function load_user_by_request(Request $request){
    	return $this->get('fos_user.user_manager')->findUserByUsername($request->attributes->get('username'));
	}

    /**
     * @Route("/", name="profile_base_default")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $user = $this->load_user_by_request($request);

        if(!$user instanceof User){
            throw $this->createNotFoundException('Page not found.');
        }


        $this->get('breadcrumbs_organizer')->showUser($user);
        

        return $this->render('profile/index.html.twig', ['user' => $user]);

    }

    /**
     * @Route("/collections", name="user_collections")
     * @Method("GET")
     */
    public function userCollectionsAction(Request $request){

    	$request_user = $this->load_user_by_request($request);

        $this->get('breadcrumbs_organizer')->listUserCollections($request_user);


    	$current_user = $this->get('app.current_user')->get();

		$collections = $this->getDoctrine()->getRepository(Collection::class)->findByOwner($request_user, $current_user);

    	if($current_user && $request_user->getUsername() === $current_user->getUsername()){

	    	return $this->render('collection/index.html.twig', ['collections' => $collections, 'label' => 'collection.list_my']);

    	}else{
	    
	    	return $this->render('collection/index.html.twig', ['collections' => $collections, 'label' => 'collection.list_name', 'name'=>$request_user->getUsername()]);

    	}


    }


	/**
     * @Route("/albums", name="user_albums")
     * @Method("GET")
     */
    public function userAlbumsAction(Request $request){

    	$request_user = $this->load_user_by_request($request);

        $this->get('breadcrumbs_organizer')->listUserAlbums($request_user);


        $current_user = $this->get('app.current_user')->get();

    	$albums = $this->getDoctrine()->getRepository(Album::class)->findByOwner($request_user, $current_user);

    	if($current_user && $request_user->getUsername() === $current_user->getUsername()){

	    	return $this->render('album/index.html.twig', ['albums' => $albums, 'label' => 'album.list_my']);

    	}else{
	    
	    	return $this->render('album/index.html.twig', ['albums' => $albums, 'label' => 'album.list_name', 'name'=>$request_user->getUsername()]);

    	}


    }

}