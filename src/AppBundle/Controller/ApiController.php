<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/hello")
     */
    public function helloAction()
    {
        return new JsonResponse(array('world' => 'world'));
    }

    /**
     * @Route("/todos")
     */
    public function todosAction()
    {
        $todos = array(
            array('text'=>'learn angular', 'done'=>true),
            array('text'=>'build an angular app', 'done'=>false),
            );
        return new JsonResponse($todos);
    }
}
