<?php

// src/AppBundle/Controller/Profile/CollectionController.php

namespace AppBundle\Controller\Profile;

use AppBundle\Entity\Collection;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CollectionController extends Controller
{
    
    public function showCollectionsAction(Request $request, $username)
    {
        
        // $collections = $this->getDoctrine()
        //     ->getRepository('AppBundle:Collection')
        //     ->findLatest();

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

		// $usr= $this->getUser();

        if(!$user){
            throw $this->createNotFoundException('Page not found.');
        }

		$collections = $user->getCollections();


        return $this->render(
            'profile/collections.html.twig', Array(
            	'collections'=>$collections,
                'user'=>$user
        	)
        );

    }

    public function newAction(Request $request){

        $collection = new Collection();
        $collection->setName('My Neat Collection');
        $collection->setDescription('My Collection Description');

        $form = $this->createFormBuilder($collection)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('public', CheckboxType::class, Array('label'=>'Make collection public', 'required'=>false))
            ->add('save', SubmitType::class, Array('label'=>'Create Collection'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $collection = $form->getData();

            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $collection->setOwner($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();

            return $this->redirectToRoute('collections', Array('username'=>$user->getUsername()));

        }

        return $this->render('collection/new.html.twig', Array(
            'form'=>$form->createView(),
        ));

    }


}