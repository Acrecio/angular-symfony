<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/{username}/salt", requirements={"username" = "\w+"})
     */
    public function saltAction($username)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->findUserByUsername($username);
        if ( is_null($user) )
        {
            throw new HttpException(400, "Error User Not Found");
        }

        return new JsonResponse(array('salt' => $user->getSalt()));
    }

    /**
     * @Route("/{username}/info", requirements={"username" = "\w+"})
     */
    public function infoAction($username)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->findUserByUsername($username);
        if ( is_null($user) )
        {
            throw new HttpException(400, "Error User Not Found");
        }

        return new JsonResponse(array('username' => array('salt' => $user->getSalt())));
    }

}
