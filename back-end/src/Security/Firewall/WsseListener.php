<?php

namespace App\Security\Firewall;

use App\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class WsseListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $wsseRegex = '/UsernameToken Username="(?P<username>[^"]+)", PasswordDigest="(?P<digest>[^"]+)", Nonce="(?P<nonce>[a-zA-Z0-9+\/]+={0,2})", Created="(?P<created>[^"]+)"/';
        
        $wsseHeader = $request->headers->has('x-wsse') ? stripslashes($request->headers->get('x-wsse')) : "";
        
        if (!$request->headers->has('x-wsse') || 1 !== preg_match($wsseRegex, $wsseHeader, $matches)) {
            return;
        }

        $token = new WsseUserToken();
        $token->setUser($matches['username']);

        $token->digest  = $matches['digest'];
        $token->nonce   = $matches['nonce'];
        $token->created = $matches['created'];

        try {
            // Authenticate user
            $authToken = $this->authenticationManager->authenticate($token);
            // Store token for
            $this->tokenStorage->setToken($authToken);
            return;
        } catch (AuthenticationException $failed) {
            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            $token = $this->tokenStorage->getToken();
            if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
                $this->tokenStorage->setToken(null);
            }
            return;
        }

        // By default deny authorization
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
