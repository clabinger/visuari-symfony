<?php

// src/AppBundle/Controller/UploadController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\PhotoType;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller used to manage uploads
 *
 * @Route("/upload")
 *
 */
class UploadController extends Controller
{
     /**
     * @Route("", name="upload")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function uploadAction(Request $request)
    {

        // $filesystem = $this->get('photoalbums_filesystem');

        // dump($filesystem);

        // $filesystem->put('test.txt', 'pandas');

        $photo = new Photo();

        $form = $this->createForm(PhotoType::class, $photo);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $photo = $form->getData();

            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            // $photo->setOwner($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();

            $this->addFlash('success', $this->get('translator')->trans('photo.created_successfully'));

            return $this->redirectToRoute('user_collections', Array('username'=>$user->getUsername()));

        }

        return $this->render('upload/upload.html.twig', Array(
            'photo' => $photo,
            'form'  => $form->createView(),
        ));

    }

}