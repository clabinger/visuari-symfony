<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class LuckyController extends Controller
{
    
    public function numberAction($count, Request $request)
    {
        $numbers = Array();

        if(!is_numeric($count)){
            throw $this->createNotFoundException('You must provide a number.');
        }

        for($i=0; $i<$count; $i++){
            $numbers[] = rand(0, 100);
        }

        $numbersList = implode(', ', $numbers);

        $flavor = $request->query->get('flavor');

        $html = $this->render(
            'lucky/number.html.twig', 
            array('luckyNumberList' => $numbersList, 'flavor' => $flavor)
        )->getContent();

        return new Response($html);
    }

    
    public function apiNumberAction()
    {
        $data = array(
            'lucky_number' => rand(0, 100),
        );

        return new JsonResponse($data);

        // Or: return $this->json($data);
    }

}