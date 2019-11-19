<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Rest\Get("/hello")
     */
    public function helloAction()
    {
        return $this->json(['hello' => 'world']);
    }
}