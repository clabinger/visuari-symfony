<?php

// src/AppBundle/Controller/AlbumController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;

use AppBundle\Form\AlbumType;
use AppBundle\Form\UploadPhotosToAlbumType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Controller used to manage albums
 *
 * @Route("/albums")
 *
 */
class AlbumController extends Controller {
	
	/**
     * @Route("", defaults={"page": 1}, name="album_index")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="album_index_paginated")
     * @Method("GET")
     */
	public function indexAction($page)
    {
        $albums = $this->getDoctrine()->getRepository(Album::class)->findLatest($page);

        // $albums = $this->getDoctrine()
        // ->getRepository('AppBundle:Album')
        // // ->findByPublic(true);
        // ->findAll();

        return $this->render('album/index.html.twig', ['albums' => $albums, 'label' => 'album.list_all']);
    }


	/**
     * @Route("/{id}", name="show_album", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(Album $album){

        // Check voters
        $this->denyAccessUnlessGranted('view', $album, $this->get('translator')->trans('album.view.denied'));

        $breadcrumbs = $this->get("white_october_breadcrumbs");

        $collection = $album->getCollection();

        $breadcrumbs->addItem('collection.data.name', $this->get("router")->generate("show_collection", ['id'=>$collection->getId()]), ['%name%'=>$collection->getName()]);

        $breadcrumbs->addItem('album.data.name', NULL, ['%name%'=> $album->getName()] );

    	return $this->render('album/show.html.twig', ['album'=>$album]);

    }


    /**
     * @Route("/new", name="new_album")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function newAction(Request $request){

    	$album = new Album();

    	$form = $this->createForm(AlbumType::class, $album);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		$album = $form->getData();

            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $album->setOwner($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($album);
            $entityManager->flush();

            $this->addFlash('success', $this->get('translator')->trans('album.created_successfully'));

            return $this->redirectToRoute('show_album', ['id' => $album->getID()]);

    	}

    	return $this->render('album/new.html.twig', Array(
            'album' => $album,
            'form'       => $form->createView(),
        ));

    }


    /**
     * @Route("/{id}/edit",  requirements={"id": "\d+"}, name="edit_album")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function editAction(Album $album, Request $request){

        // Check voters
        $this->denyAccessUnlessGranted('edit', $album, $this->get('translator')->trans('album.edit_not_allowed'));

        $entityManager = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(AlbumType::class, $album);
        $deleteForm = $this->createDeleteForm($album);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', $this->get('translator')->trans('album.updated_successfully'));
            
            return $this->redirectToRoute('show_album', ['id' => $album->getId()]);
        }

        return $this->render('album/edit.html.twig', [
            'album'  => $album,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);


    }


    /**
     * @Route("/{id}/upload",  requirements={"id": "\d+"}, name="upload_to_album")
     * @Method({"GET", "POST", "PUT"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function uploadAction(Album $album, Request $request){

        // Check voters
        $this->denyAccessUnlessGranted('edit', $album, $this->get('translator')->trans('album.edit_not_allowed_photos'));

        $entityManager = $this->getDoctrine()->getManager();


        $uploadForm = $this->createForm(UploadPhotosToAlbumType::class, $album, Array(
            'action'=>$this->container->get('oneup_uploader.templating.uploader_helper')->endpoint('photoalbums_local')
        ));

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', $this->get('translator')->trans('album.photos_added_successfully'));
            
            return $this->redirectToRoute('show_album', ['id' => $album->getId()]);
        }

        return $this->render('album/upload.html.twig', [
            'album'  => $album,
            'upload_form'   => $uploadForm->createView(),
        ]);


    }




    /**
     * Creates a form to delete a Album entity by id.
     *
     * This is necessary because browsers don't support HTTP methods different
     * from GET and POST. Since the controller that removes the records expects
     * a DELETE method, the trick is to create a simple form that *fakes* the
     * HTTP DELETE method.
     * See http://symfony.com/doc/current/cookbook/routing/method_parameters.html.
     *
     * @param Album $album The album object
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Album $album)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('album_delete', ['id' => $album->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


     /**
     * Deletes a Album entity.
     *
     * @Route("/{id}", name="album_delete")
     * @Method("DELETE")
     * @Security("album.isOwner(user)")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     * The isAuthor() method is defined in the AppBundle\Entity\Post entity.
     */
    public function deleteAction(Request $request, Album $album)
    {

        // Check voters
        $this->denyAccessUnlessGranted('edit', $album, $this->get('translator')->trans('album.delete_not_owner'));

        $form = $this->createDeleteForm($album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($album);
            $entityManager->flush();

            $this->addFlash('success', 'album.deleted_successfully');
        }

        return $this->redirectToRoute('user_collections');
    }
}