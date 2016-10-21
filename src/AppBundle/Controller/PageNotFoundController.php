<?php

// src/AppBundle/Controller/PageNotFoundController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller used to manage 404 pages
 */
class PageNotFoundController extends Controller
{
    
    public function showAction(Request $request)
    {
        throw new NotFoundHttpException();
    }
}
