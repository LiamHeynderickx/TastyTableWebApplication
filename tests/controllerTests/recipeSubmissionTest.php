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
        $client = static::createClient();
        $crawler = $client->request('GET', '/recipeSubmission');

        $form = $crawler->selectButton('Submit')->form();

        // Fill in the form fields with valid data
        $form['form[recipeName]'] = 'Test Recipe';
        $form['form[recipeDescription]'] = 'This is a test recipe.';
        $form['form[cost]'] = '2';
        $form['form[time]'] = 30;
        $form['form[servings]'] = 4;
        $form['form[diet]'] = 'vegetarian';
        $form['form[type]'] = 'dinner';
        $form['form[calories]'] = 350;
        $form['form[fat]'] = 15;
        $form['form[carbs]'] = 50;
        $form['form[protein]'] = 20;
        $form['form[ingredients]'] = json_encode(['ingredient1', 'ingredient2']);
        $form['form[ingredientsAmounts]'] = json_encode(['100g', '200g']);
        $form['form[ingredientsUnits]'] = json_encode(['grams', 'grams']);
        $form['form[instructions]'] = json_encode(['Step 1', 'Step 2']);

        //file upload is tested in another function

        // Submit the form
        $client->submit($form);

        // Assertions
        $this->assertTrue($client->getResponse()->isRedirect('/recipeDisplay/[163]')); // ID needs to be adjusted (last db recipeId +1)
    }

    public function testMissingRequiredFields()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/recipeSubmission');

        $form = $crawler->selectButton('Submit')->form();

        // Submit the form without filling in any fields
        $client->submit($form);

        // Assertions
        $this->assertSelectorTextContains('.form-errors', 'This value should not be blank.');
        // Check for validation errors displayed on the form
    }

    public function testAccessDeniedForUnauthenticatedUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/recipeSubmission');

        // Assertions
        $this->assertResponseRedirects('/login');
        // Check if the user is redirected to the login page
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