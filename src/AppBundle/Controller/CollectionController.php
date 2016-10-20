<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Collection;
use AppBundle\Entity\Album;

use AppBundle\Form\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Controller used to manage collections
 *
 * @Route("/collections")
 *
 */
class CollectionController extends Controller {
	
	/**
     * @Route("", defaults={"page": 1}, name="collection_index")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="collection_index_paginated")
     * @Method("GET")
     */
	public function indexAction($page)
    {
        $collections = $this->getDoctrine()->getRepository(Collection::class)->findLatest($this->get('app.current_user')->get(), $page);

        $this->get('breadcrumbs_organizer')->listCollections();

        // $collections = $this->getDoctrine()
        // ->getRepository('AppBundle:Collection')
        // ->findByPublic(true);

        return $this->render('collection/index.html.twig', ['collections' => $collections, 'label' => 'collection.list_all']);
    }


	/**
     * @Route("/{id}", name="show_collection", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(Collection $collection){

        // Check voters
        $this->denyAccessUnlessGranted('view', $collection, $this->get('translator')->trans('collection.view.denied'));

        $this->get('breadcrumbs_organizer')->showCollection($collection);

        $albums = $this->getDoctrine()->getRepository(Album::class)->findByCollection($collection, $this->get('app.current_user')->get() );

    	return $this->render('collection/show.html.twig', ['collection'=>$collection, 'albums'=>$albums]);

    }


    /**
     * @Route("/new", name="new_collection")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function newAction(Request $request){

    	$collection = new Collection();

    	$form = $this->createForm(CollectionType::class, $collection);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		$collection = $form->getData();

            $user = $this->get('app.current_user')->get();

            $collection->setOwner($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();

            $this->addFlash('success', $this->get('translator')->trans('collection.created_successfully'));

            return $this->redirectToRoute('user_collections', Array('username'=>$user->getUsername()));

    	}

    	return $this->render('collection/new.html.twig', Array(
            'collection' => $collection,
            'form'       => $form->createView(),
        ));

    }


    /**
     * @Route("/{id}/edit",  requirements={"id": "\d+"}, name="edit_collection")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function editAction(Collection $collection, Request $request){

    	// if (null === $this->getUser() || !$collection->isOwner($this->getUser())) {
     //        throw $this->createAccessDeniedException($this->get('translator')->trans('collection.edit_not_owner'));
     //    }

        // Check voters
        $this->denyAccessUnlessGranted('edit', $collection, $this->get('translator')->trans('collection.edit_not_owner'));

        $entityManager = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(CollectionType::class, $collection);
        $deleteForm = $this->createDeleteForm($collection);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', $this->get('translator')->trans('collection.updated_successfully'));
            
            return $this->redirectToRoute('show_collection', ['id' => $collection->getId()]);
        }

        return $this->render('collection/edit.html.twig', [
            'collection'  => $collection,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);


    }

    /**
     * Creates a form to delete a Collection entity by id.
     *
     * This is necessary because browsers don't support HTTP methods different
     * from GET and POST. Since the controller that removes the records expects
     * a DELETE method, the trick is to create a simple form that *fakes* the
     * HTTP DELETE method.
     * See http://symfony.com/doc/current/cookbook/routing/method_parameters.html.
     *
     * @param Collection $collection The collection object
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Collection $collection)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('collection_delete', ['id' => $collection->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


     /**
     * Deletes a Collection entity.
     *
     * @Route("/{id}", name="collection_delete")
     * @Method("DELETE")
     * @Security("collection.isOwner(user)")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     * The isAuthor() method is defined in the AppBundle\Entity\Post entity.
     */
    public function deleteAction(Request $request, Collection $collection)
    {

        // Check voters
        $this->denyAccessUnlessGranted('edit', $collection, $this->get('translator')->trans('collection.delete_not_owner'));

        $form = $this->createDeleteForm($collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($collection);
            $entityManager->flush();

            $this->addFlash('success', 'collection.deleted_successfully');
        }

        return $this->redirectToRoute('user_collections');
    }
}