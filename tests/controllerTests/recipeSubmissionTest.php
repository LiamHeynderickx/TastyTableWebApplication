<?php

namespace App\Tests\controllerTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Recipes;
use App\Entity\User;
use App\Entity\Comments;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class recipeSubmissionTest extends WebTestCase {

    public function testSuccessfulRecipeSubmission()
    {
        // Create a client with a mocked session
        $client = static::createClient();
        $client->enableProfiler();

        // Get the container and mock the session service
        $container = $client->getContainer();
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturnMap([
            ['isOnline', true],
            ['userId', 1],
        ]);
        $container->set('session', $sessionMock);

        // Create a new recipe submission form
        $crawler = $client->request('GET', '/recipeSubmission');

        // Debugging: Output HTML content of the response
        echo $client->getResponse()->getContent();

        // Check if the form exists in the response
        $form = $crawler->filter('form[name="recipe_form"]')->form();

        // Fill in the form fields with valid data
        $form['recipe_form[recipeName]'] = 'Test Recipe';
        $form['recipe_form[recipeDescription]'] = 'This is a test recipe.';
        $form['recipe_form[cost]'] = 2;
        $form['recipe_form[time]'] = 30;
        $form['recipe_form[servings]'] = 4;
        $form['recipe_form[diet]'] = 'vegetarian';
        $form['recipe_form[type]'] = 'dinner';
        $form['recipe_form[calories]'] = 350;
        $form['recipe_form[fat]'] = 15;
        $form['recipe_form[carbs]'] = 50;
        $form['recipe_form[protein]'] = 20;

        // Fill in hidden fields for ingredients and instructions
        $form['recipe_form[ingredients]']->setValue(json_encode(['ingredient1', 'ingredient2']));
        $form['recipe_form[ingredientsAmounts]']->setValue(json_encode(['100g', '200g']));
        $form['recipe_form[ingredientsUnits]']->setValue(json_encode(['grams', 'grams']));
        $form['recipe_form[instructions]']->setValue(json_encode(['Step 1', 'Step 2']));

        // Submit the form
        $client->submit($form);

        // Check if the form submission redirects to the expected recipe display page
        $this->assertTrue($client->getResponse()->isRedirect('/recipeDisplay/127'));

        // Follow the redirect
        $crawler = $client->followRedirect();

        // Assert that the final response is successful
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Test Recipe');
    }

    public function testAccessDeniedForUnauthenticatedUser()
    {
        $client = static::createClient();

        // Make a request to the recipeSubmission endpoint
        $crawler = $client->request('GET', '/recipeSubmission');

        // Assert that the response is a redirect
        $this->assertTrue($client->getResponse()->isRedirect());

        // Assert that the redirect is to the correct URL (http://127.0.0.1:8000/)
        $this->assertEquals('/', $client->getResponse()->getTargetUrl());
    }

    public function testFileUpload() // unfinished
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/recipeSubmission');

        $form = $crawler->selectButton('Submit')->form();

        // Simulate file upload
        $picturePath = 'test.jpg';
        $form['form[picture]']->upload($picturePath);

        // Submit the form
        $client->submit($form);

        // Assertions
        $this->assertTrue($client->getResponse()->isRedirect('/recipeDisplay/[163]'));
        // Check if the file is correctly uploaded and saved
    }

}