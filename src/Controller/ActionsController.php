<?php

namespace Splash\Connectors\FakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActionsController extends Controller
{

    /**
     * @abstract    Index Fake Controller Action
     *
     * @return  Response
     */
    public function indexAction()
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array("result" => "Ok"));
    }
    
    /**
     * @abstract    Dummy Fake Controller Action
     *
     * @return  Response
     */
    public function dummyAction()
    {
        //====================================================================//
        // Return Dummy Response
        return new Response("Yeah!!  You touched Faker Dummy Action!");
    }
    
    /**
     * @abstract    Fail Test Fake Controller Action
     *
     * @return  Response
     */
    public function failAction()
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array("result" => "Ko"), 500);
    }
}
