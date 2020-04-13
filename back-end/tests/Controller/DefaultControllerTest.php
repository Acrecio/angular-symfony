<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testGetIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostLogin()
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

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Get the user secret (we use the salt because it can be disclosed)
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("e4TNCCgLvPbDRh7ih+pK58pab0NToFzdZHuPmA0e", $content['secret']);
    }
}
