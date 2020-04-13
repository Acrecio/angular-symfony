<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
