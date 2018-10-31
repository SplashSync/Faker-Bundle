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
     * @param   Request $request
     * 
     * @return  Response
     */
    public function indexAction(Request $request)
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array("result" => "Ok"));
    }
    
    /**
     * @abstract    Dummy Fake Controller Action
     * 
     * @param   Request $request
     * 
     * @return  Response
     */
    public function dummyAction(Request $request)
    {
        //====================================================================//
        // Return Dummy Response
        return new Response("Yeah!!  You touched Faker Dummy Action!");
    }
    
    /**
     * @abstract    Fail Test Fake Controller Action
     * 
     * @param   Request $request
     * 
     * @return  Response
     */
    public function failAction(Request $request)
    {
        //====================================================================//
        // Return Dummy Response
        return new JsonResponse(array("result" => "Ko"), 500);
    }    
}
