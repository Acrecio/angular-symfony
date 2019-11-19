<?php

namespace App\Security\Authentication\Provider;

use App\Security\Authentication\Token\WsseUserToken;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WsseProvider implements AuthenticationProviderInterface
{
    private const TOKEN_DURATION = 300;
    private $userProvider;
    private $cachePool;

    public function __construct(UserProviderInterface $userProvider, CacheItemPoolInterface $cachePool)
    {
        $this->userProvider = $userProvider;
        $this->cachePool = $cachePool;
    }

    public function authenticate(TokenInterface $token)
    {
        // Try to load user from database
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        // Check digest and replay attempts and return token with user roles
        if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getSalt())) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed.');
    }

    /**
     * This function is specific to Wsse authentication and is only used to help this example
     *
     * For more information specific to the logic here, see
     * https://github.com/symfony/symfony-docs/pull/3134#issuecomment-27699129
     */
    protected function validateDigest($digest, $nonce, $created, $secret)
    {
        // Check created time is not in the future
        if (strtotime($created) > time()) {
            return false;
        }
        
        // Expire timestamp after 5 minutes
        if (time() - strtotime($created) > self::TOKEN_DURATION) {
            return false;
        }
        
        // Try to fetch the cache item from pool
        $cacheItem = $this->cachePool->getItem(md5($nonce));
        
        // Validate that the nonce is *not* in cache
        // if it is, this could be a replay attack
        if ($cacheItem->isHit()) {
            // In a real world application you should throw a custom
            // exception extending the AuthenticationException
            throw new AuthenticationException('Previously used nonce detected');
        }

        // Store the item in cache for 5 minutes
        $cacheItem->set(null)->expiresAfter(self::TOKEN_DURATION);
        $this->cachePool->save($cacheItem);

        // Validate Secret
        $expected = base64_encode(sha1(base64_decode($nonce) . $created . $secret, true));

        return hash_equals($expected, $digest);
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken;
    }
}
