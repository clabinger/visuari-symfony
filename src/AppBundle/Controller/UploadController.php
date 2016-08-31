<?php

// src/AppBundle/Controller/UploadController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\JsonResponse;

class UploadController extends Controller
{
    
    public function uploadAction(Request $request)
    {
        
        $html = $this->render(
            'upload/upload.html.twig'
        )->getContent();

        return new Response($html);
    }


}