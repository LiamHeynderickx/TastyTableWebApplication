<?php
//
//namespace App\Tests\htmlTests;
//
//use Symfony\Component\Panther\PantherTestCase;
//
//class LoginTest extends PantherTestCase
//{
//    public static function setUpBeforeClass(): void
//    {
//        // Ensure to pass the options as an empty array instead of null and change to an available port
//        self::startWebServer(['-S', '127.0.0.1:9081'], '127.0.0.1', 9081);
//    }
//
//    public function testLoginPageIsSuccessful(): void
//    {
//        $client = static::createPantherClient(['browser' => PantherTestCase::CHROME, 'external_base_uri' => 'http://127.0.0.1:9081']);
//        $crawler = $client->request('GET', 'https://127.0.0.1:8000/'); // Update the path as needed
//
//        // Assert that the response status code is 200; 200 = successful request
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//        // Assert that the h1 tag contains the text "Login"
//        $this->assertSelectorTextContains('h1', 'Login');
//
//        // Assert that the email and password fields are present
//        $this->assertGreaterThan(
//            0,
//            $crawler->filter('input[name="email"]')->count(),
//            'Missing email field'
//        );
//        $this->assertGreaterThan(
//            0,
//            $crawler->filter('input[name="password"]')->count(),
//            'Missing password field'
//        );
//    }
//
//    public function testEmptyLoginFields(): void
//    {
//        $client = static::createPantherClient(['browser' => PantherTestCase::CHROME, 'external_base_uri' => 'http://127.0.0.1:9081']);
//        $crawler = $client->request('GET', 'https://127.0.0.1:8000/'); // Update the path as needed
//
//        // Select the login button and click it without filling the fields
//        $form = $crawler->selectButton('Login')->form();
//        $crawler = $client->submit($form);
//
//        // Assert that an error message is displayed (adjust based on your form validation logic)
//        $this->assertSelectorTextContains('body', 'This field is required', 'Expected an error message for empty fields.');
//    }
//
//    public function testLoginWithInput(): void
//    {
//        $client = static::createPantherClient(['browser' => PantherTestCase::CHROME, 'external_base_uri' => 'http://127.0.0.1:9081']);
//        $crawler = $client->request('GET', 'https://127.0.0.1:8000/'); // Update the path as needed
//
//        // Fill in the login form with some input
//        $form = $crawler->selectButton('Login')->form([
//            'email' => 'test@example.com',
//            'password' => 'password123',
//        ]);
//        $client->submit($form);
//
//        // Assert that the response is a redirect to the home page or another page indicating a successful login
//        $this->assertTrue($client->getResponse()->isRedirect(), 'Expected a redirect after form submission.');
//
//        // Follow the redirect and check the final page
//        $crawler = $client->followRedirect();
//        $this->assertSelectorExists('h1', 'Home');
//    }
//
//    public function testButtonClickTriggersJS(): void
//    {
//        $client = static::createPantherClient(['browser' => PantherTestCase::CHROME, 'external_base_uri' => 'http://127.0.0.1:9081']);
//        $crawler = $client->request('GET', '/path/to/your/login.html'); // Update the path as needed
//
//        // Simulate button click
//        $crawler->filter('#loginBtn')->click();
//
//        // Add assertions to check the expected behavior after button click
//        // For example, if the button click shows an alert or changes the DOM, verify those changes
//        $this->assertSelectorTextContains('body', 'Login button clicked');
//    }
//}
