<?php

namespace AppBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use AppBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class WsseListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;
    protected $logger;

    public function __construct(SecurityContextInterface $securityContext, 
                                AuthenticationManagerInterface $authenticationManager,
                                LoggerInterface $logger)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->logger = $logger;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // Check if authentication Token is present
        if ($request->headers->has('x-wsse')) {

            // Token parser
            $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';

            if (preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {
                $token = new WsseUserToken();
                $token->setUser($matches[1]);

                $token->digest   = $matches[2];
                $token->nonce    = $matches[3];
                $token->created  = $matches[4];

                try {
                    // Authentication process 
                    $authToken = $this->authenticationManager->authenticate($token);
                    $this->securityContext->setToken($authToken);
                    
                    return;
                } catch (AuthenticationException $failed) {
                    $failedMessage = 'WSSE Login failed for '.$token->getUsername().'. Why ? '.$failed->getMessage();
                    $this->logger->err($failedMessage);

                    // To deny the authentication clear the token. This will redirect to the login page.
                    // $token = $this->securityContext->getToken();
                    // if ( $token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
                    //     $this->securityContext->setToken(null);
                    // }

                    // Deny authentication with a '403 Forbidden' HTTP response
                    $response = new Response();
                    $response->setStatusCode(403);
                    $response->setContent($failedMessage);
                    $event->setResponse($response);

                    return;
                } catch( NonceExpiredException $expired) {
                    $failedMessage = 'WSSE Nonce Expired for '.$token->getUsername().'. Why ? '.$failed->getMessage();
                    $this->logger->err($failedMessage);

                    // Deny authentication with a '403 Forbidden' HTTP response
                    $response = new Response();
                    $response->setStatusCode(403);
                    $response->setContent($failedMessage);
                    $event->setResponse($response);

                    return;
                }

                // By default deny authorization
                $response = new Response();
                $response->setStatusCode(403);
                $event->setResponse($response);
            }
        }

        // By default deny authentication
        $response = new Response();
        $response->setStatusCode(403);
        $event->setResponse($response);
        
    }
}
