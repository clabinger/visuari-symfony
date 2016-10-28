<?php

// src/AppBundle/Controller/AlbumController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Album_Photo;

use AppBundle\Form\AlbumType;
use AppBundle\Form\ItemPermissionsType;
use AppBundle\Form\UploadPhotosToAlbumType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Controller used to manage albums
 *
 * @Route("/albums")
 *
 */
class AlbumController extends Controller {
	
    private function applyUploadOrder(Album $album){

        if($album->getPendingUpload()===0){ // There is no pending upload action on this album
            return $album;
        }

        $criteria = Criteria::create()
            ->orderBy(array("uploadPosition" => Criteria::ASC, "position" => Criteria::ASC));

        $photos = $album->getPhotos()->matching($criteria);

        $pos = 0;

        foreach($photos as $photo){
            $photo->setPosition(++$pos);
            $photo->setUploadPosition(0);
            $photo->setChangeDate($photo->getChangeDate());
        }

        $album->setPendingUpload(0);

        $entityManager = $this->getDoctrine()->getManager();

        // Don't observe lifecycle callbacks for this request
        $entityManager->getClassMetadata(Album_Photo::class)->lifecycleCallbacks = Array();

        $entityManager->persist($album);
        $entityManager->flush();

        return $album;

    }

	/**
     * @Route("", defaults={"page": 1}, name="album_index")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="album_index_paginated")
     * @Method("GET")
     */
	public function indexAction($page)
    {

        $user = $this->get('app.current_user')->get();


        $albums = $this->getDoctrine()->getRepository(Album::class)->findLatest($user, $page);

        return $this->render('album/index.html.twig', ['albums' => $albums, 'label' => 'album.list_all']);
    }


	/**
     * @Route("/{id}", name="show_album", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(Album $album){

        // Put photos in uploaded order, if this step has not been performed since photos were uploaded into the album.
        $album = $this->applyUploadOrder($album);

        // Check voters
        $this->denyAccessUnlessGranted('view', $album, $this->get('translator')->trans('album.view.denied'));

        $this->get('breadcrumbs_organizer')->showAlbum($album);

    	return $this->render('album/show.html.twig', ['album'=>$album]);

    }


    /**
     * @Route("/new/{collection}", name="new_album", requirements={"collection": "\d+"}, defaults={"collection" = null})
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function newAction(Request $request){

    	$album = new Album();

    	$form = $this->createForm(AlbumType::class, $album);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		$album = $form->getData();

            $user = $this->get('app.current_user')->get();

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

        $this->get('breadcrumbs_organizer')->editAlbum($album);

        $entityManager = $this->getDoctrine()->getManager();


        // START get list of photos for the purpose of deleting photos from album
        $originalPhotos = new ArrayCollection();

        // Create an ArrayCollection of the current Photo objects in the database
        foreach ($album->getPhotos() as $photo) {
            $originalPhotos->add($photo);
        }
        // END get list of photos


        $editForm = $this->createForm(AlbumType::class, $album);
        $deleteForm = $this->createDeleteForm($album);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // START Remove any deleted photos
            foreach ($originalPhotos as $photo) {
                if (false === $album->getPhotos()->contains($photo)) {
                    $entityManager->remove($photo);
                }
            }
            // END Remove any deleted photos

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
     * @Route("/{id}/permissions",  requirements={"id": "\d+"}, name="edit_album_permissions")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function editPermissionsAction(Album $album, Request $request){

        // Check voters
        $this->denyAccessUnlessGranted('edit_permissions', $album, $this->get('translator')->trans('album.edit_not_allowed_permissions'));

        $this->get('breadcrumbs_organizer')->permissionsAlbum($album);

        $entityManager = $this->getDoctrine()->getManager();

        // START get list of permissions for the purpose of persisting new permissions to the DB / deleting permissions that were deleted from DOM
        $originalPermissions = new ArrayCollection();

        // Create an ArrayCollection of the current Photo objects in the database
        foreach ($album->getPermissions() as $permission) {
            $originalPermissions->add($permission);
        }
        // END get list of permissions

        $editForm = $this->createForm(ItemPermissionsType::class, $album);

        $editForm->handleRequest($request);

        $user = $this->get('app.current_user')->get();

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // START persist any new permissions
            foreach ($album->getPermissions() as $permission) {
                if (false === $originalPermissions->contains($permission)) {
                    $permission->setAlbum($album);
                    $permission->setGrantedBy($user);
                    $entityManager->persist($permission);
                }
            }
            // END persist any new permissions

            // START Remove any deleted permissions
            foreach ($originalPermissions as $permission) {
                if (false === $album->getPermissions()->contains($permission)) {
                    $entityManager->remove($permission);
                }
            }
            // END Remove any deleted permissions

            $entityManager->flush();

            $this->addFlash('success', $this->get('translator')->trans('album.updated_successfully'));
            
            return $this->redirectToRoute('show_album', ['id' => $album->getId()]);
        }

        return $this->render('album/edit_permissions.html.twig', [
            'album'  => $album,
            'edit_form'   => $editForm->createView(),
        ]);


    }


    /**
     * @Route("/{id}/upload",  requirements={"id": "\d+"}, name="upload_to_album")
     * @Method({"GET", "POST", "PUT"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function uploadAction(Album $album, Request $request){

        // Put photos in uploaded order, if this step has not been performed since photos were uploaded into the album.
        $album = $this->applyUploadOrder($album);

        // Check voters
        $this->denyAccessUnlessGranted('edit', $album, $this->get('translator')->trans('album.edit_not_allowed_photos'));

        $this->get('breadcrumbs_organizer')->uploadToAlbum($album);

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