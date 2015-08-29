<?php

namespace AppBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use AppBundle\Security\Authentication\Token\WsseUserToken;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class WsseProvider extends ContainerAware implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct( UserProviderInterface $userProvider, $cacheDir )
    {
        $this->userProvider = $userProvider;
        $this->cacheDir = $cacheDir;
    }

    // Authenticate webservice client
    public function authenticate( TokenInterface $token )
    {
        // Load specified user
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        // Verify Token and register it
        if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword())) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed. For user : '.$user);
    }

    // Token validator
    public function validateDigest( $digest, $nonce, $created, $secret )
    {
        // Generate created Token time difference
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $then = new \Datetime($created, new \DateTimeZone('UTC'));
        $diff = $now->diff( $then, true );

        // Check created time is not in the future
        if (strtotime($created) > time()) {
          throw new AuthenticationException("Back to the future...");
        }

        // Validate timestamp is recent within 5 minutes
        $seconds = time() - strtotime($created);
        if ( $seconds > 300 )
        {
            throw new AuthenticationException('Expired timestamp.  Seconds: ' . $seconds);
        }

        // Validate nonce is unique within 5 minutes
        if (file_exists($this->cacheDir.'/'.$nonce) && file_get_contents($this->cacheDir.'/'.$nonce) + 300 > time()) {
            throw new NonceExpiredException('Previously used nonce detected');
        }
        if ( !is_dir($this->cacheDir) ) {
            mkdir($this->cacheDir, 0777, true);
        }
        file_put_contents($this->cacheDir.'/'.$nonce, time());

        // Validate Secret
        $expected = base64_encode(sha1(base64_decode($nonce).$created.$secret, true));

        // Return TRUE if our newly-calculated digest is the same as the one provided in the validateDigest() call
        return $expected === $digest;
    }

    public function supports( TokenInterface $token )
    {
        return $token instanceof WsseUserToken;
    }
}
