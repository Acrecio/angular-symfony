<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHello()
    {
        $_GET['debug'] = 1;

        $client = static::createClient();

        $crawler = $client->request('GET', '/api/hello');

        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode()
        );

        $username = 'admin';
        $password = 'adminpass';

        $nonce = md5(rand());

        $created = date("Y-m-d\TH:i:s\Z");

        $digest = base64_encode(sha1($nonce.$created.$password, true));

        $b64nonce = base64_encode($nonce);

        $token = 'UsernameToken Username="'.$username.'", PasswordDigest="'.$digest.'", Nonce="'.$b64nonce.'", Created="'.$created.'"';

        $crawler = $client->request(
                            'GET', 
                            '/api/hello', 
                            array(), 
                            array(), 
                            array(
                                'X-WSSE' => $token
                            )
        );

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
    }
}
