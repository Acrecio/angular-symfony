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
        $client = static::createClient();
        // User created by running doctrine fixtures
        $client->request('POST', '/login', ['username' => 'bob', 'password' => 'Abc123']);
        // Get user secret
        $content = json_decode($client->getResponse()->getContent(), true);

        // User created by running doctrine fixtures
        $username = 'bob';
        // User secret returned after login
        $secret = $content['secret'];
        // Generate a random string to prevent replay attacks
        $nonce = base64_encode(substr(md5(rand()), 0, 10));
        // Token work for 5 minutes
        $created = date("Y-m-d\TH:i:s\Z", strtotime('now -2 minute'));
        // Generate the shared secret digest
        $digest = base64_encode(sha1(base64_decode($nonce) . $created . $secret, true));
        // X-WSSE header sent
        $userToken = 'UsernameToken Username="' . $username . '", PasswordDigest="' . $digest . '", Nonce="' . $nonce . '", Created="' . $created . '"';

        $client = static::createClient();
        $client->request('GET', '/api/hello', [], [], [
            'HTTP_X-WSSE' => $userToken
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('world', $content['hello']);
    }
}