<?php

namespace App\Tests\controllerTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

class LogOutTest extends WebTestCase
{
    public function testLogOut()
    {
        $client = static::createClient();

        // Make an initial request to start the session
        $client->request('GET', '/');

        // Access the session via the request stack
        $session = $client->getRequest()->getSession();

        // Start the session and set session variables
        $session->set('isOnline', true);
        $session->set('username', 'testuser');
        $session->set('mail', 'testuser@example.com');
        $session->save();

        // Ensure the client uses the session
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        // Request the logout route
        $client->request('GET', '/LogOut');

        // Ensure redirection to the index page
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('/', $response->headers->get('Location'));

        // Follow the redirect and check that the session is cleared
        $client->followRedirect();

        // Re-fetch the session from the request stack to check its state
        $session = $client->getRequest()->getSession();
        $this->assertFalse($session->has('isOnline'));
        $this->assertFalse($session->has('username'));
        $this->assertFalse($session->has('mail'));
    }
}
