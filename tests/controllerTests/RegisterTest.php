<?php

namespace App\Tests\controllerTests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\FormInterface;


class RegisterTest extends WebTestCase
{
    private $entityManager;
    private $passwordHasher;

    public function testRegisterPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        // Check that the response is successful
        $this->assertResponseIsSuccessful();

        // Check that the form fields exist
        $this->assertSelectorExists('#username');
        $this->assertSelectorExists('#name');
        $this->assertSelectorExists('#surname');
        $this->assertSelectorExists('#nameField'); // Email
        $this->assertSelectorExists('#passwordField');
        $this->assertSelectorExists('#register');
    }
    public function testFormSubmissionExistingUser()
    {
        $client = static::createClient();

        // Mocking the EntityRepository to return an existing user
        $mockUser = new User();
        $mockUser->setUsername('existinguser');
        $mockRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);
        $mockRepository->method('findOneBy')
            ->willReturn($mockUser); // Username already taken

        $mockEm = $this->createMock(EntityManagerInterface::class);
        $mockEm->method('getRepository')
            ->willReturn($mockRepository);

        $client->getContainer()->set(EntityManagerInterface::class, $mockEm);

        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();
        $form['form[username]'] = 'existinguser';
        $form['form[name]'] = 'John';
        $form['form[surname]'] = 'Doe';
        $form['form[email]'] = 'john.doe@example.com';
        $form['form[password]'] = 'password123';

        $client->submit($form);

        // Check for alert message
        $this->assertStringContainsString(
            'Username already taken. Please choose another.',
            $client->getResponse()->getContent()
        );
    }



}
