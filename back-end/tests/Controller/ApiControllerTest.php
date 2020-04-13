<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'user', $password = 'password')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
        'username' => $username,
        'password' => $password,
      ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testGetHelloWithoutToken()
    {
        $client = static::createClient();

        $client->request('GET', '/api/hello');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testGetHelloWithToken()
    {
        // User created by running doctrine fixtures
        $credentials = ['username' => 'bob', 'password' => 'Abc123'];

        $client = $this->createAuthenticatedClient($credentials['username'], $credentials['password']);
        $client->request('GET', '/api/hello');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('world', $content['hello']);
    }
}
