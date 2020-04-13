<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

class DefaultController extends AbstractController
{
    /**
     * @Rest\Get("/")
     */
    public function index()
    {
        return new Response();
    }
}