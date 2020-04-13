<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetHelloWhithoutToken()
    {
        $client = static::createClient();

        $client->request('GET', '/api/hello');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testGetHelloWhithToken()
    {
        $credentials = ['username' => 'bob', 'password' => 'Abc123'];

        $client = static::createClient();
        // User created by running doctrine fixtures
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($credentials)
        );

        var_dump($client->getResponse()->getContent());
        // Get user secret
        $content = json_decode($client->getResponse()->getContent(), true);

        $jwtToken = $content['token'];

        $client = static::createClient();
        $client->request('GET', '/api/hello', [], [], [
      'HTTP_Authorization' => "Bearer {$jwtToken}"
    ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('world', $content['hello']);
    }
}
