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

    /**
     * @Rest\Post("/login")
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function loginAction(Request $request, UserManagerInterface $userManager, UserPasswordEncoderInterface $encoder)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $userManager->findUserByUsername($username);
        if ( is_null($user) )
        {
            throw new HttpException(400, "Error User Not Found");
        }
        if ( $user->getPassword() !== $encoder->encodePassword($user, $password) )
        {
            throw new HttpException(403, "Error User Bad Password");
        }

        return $this->json(['secret' => $user->getPassword()]);
    }
}