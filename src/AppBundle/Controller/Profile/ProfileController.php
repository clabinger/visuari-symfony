<?php

// src/AppBundle/Controller/Profile/ProfileController.php

namespace AppBundle\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileController extends Controller
{
    
    public function indexAction(Request $request, $username)
    {
        
        return $this->render('profile/index.html.twig', Array(
        	'username' => $username
        ));

    }


}