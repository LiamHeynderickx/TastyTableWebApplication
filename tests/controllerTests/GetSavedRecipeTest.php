<?php
// tests/Controller/GetSavedRecipeTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetSavedRecipeTest extends WebTestCase
{
    public function testGetSavedRecipes()
    {
        $client = static::createClient();

        // Step 1: Log in the existing user
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Login')->form();
        $form['form[email]'] = 'hackerman@gmail.com';
        $form['form[password]'] = '12345';
        $client->submit($form);

        // Check that the login was successful and user is redirected to homePage
        $this->assertTrue($client->getResponse()->isRedirect('/homePage'), 'Expected redirect to homePage after login');
        $client->followRedirect();

        // Step 2: Navigate to profile to check for saved recipes
        $crawler = $client->request('GET', '/profile?type=saved');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check that the saved recipe with recipeId: 53 is displayed
        $link = $crawler->selectLink('chug jug')->link();
        $this->assertStringContainsString('/recipeDisplay/53', $link->getUri(), 'Expected to find recipe with ID 53');
    }

//    public function testNewUserWithNoSavedRecipes()
//    {
//        $client = static::createClient();
//
//        // Step 1: Register a new user
//        $crawler = $client->request('GET', '/register');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//        $form = $crawler->selectButton('Register')->form();
//        $form['form[username]'] = 'newuser';
//        $form['form[name]'] = 'New';
//        $form['form[surname]'] = 'User';
//        $form['form[email]'] = 'newuser@example.com';
//        $form['form[password]'] = 'newpassword';
//        $client->submit($form);
//
//        // Check that the registration was successful and user is redirected to homePage
//        $this->assertTrue($client->getResponse()->isRedirect('/homePage'), 'Expected redirect to homePage after registration');
//        $client->followRedirect();
//
//        // Step 2: Log in the new user
//        $crawler = $client->request('GET', '/');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//        $form = $crawler->selectButton('Login')->form();
//        $form['form[email]'] = 'newuser@example.com';
//        $form['form[password]'] = 'newpassword';
//        $client->submit($form);
//
//        // Check that the login was successful and user is redirected to homePage
//        $this->assertTrue($client->getResponse()->isRedirect('/homePage'), 'Expected redirect to homePage after login');
//        $client->followRedirect();
//
//        // Step 3: Navigate to profile to check for saved recipes
//        $crawler = $client->request('GET', '/profile?type=saved');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//        // Check that the message for no saved recipes is displayed
//        $this->assertStringContainsString('No saved recipes found.', $client->getResponse()->getContent());
//    }


}
