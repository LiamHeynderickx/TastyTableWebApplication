<?php

namespace App\Tests\controllerTests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class IndexTest extends WebTestCase
{
    private $entityManager;
    private $passwordHasher;
    public function testIndexPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Check that the response is successful
        $this->assertResponseIsSuccessful();

        // Check that the form fields exist
        $this->assertSelectorExists('#nameField');
        $this->assertSelectorExists('#passwordField');
        $this->assertSelectorExists('#loginBtn');
    }
    public function testFormSubmissionNoUser()
    {
        $client = static::createClient();

        // Mocking the EntityRepository
        $mockRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);
        $mockRepository->method('findOneBy')
            ->willReturn(null); // No user found

        // Mocking the EntityManager to return the mocked repository
        $mockEm = $this->createMock(EntityManagerInterface::class);
        $mockEm->method('getRepository')
            ->willReturn($mockRepository);

        // Replacing the real EntityManager with the mock
        $client->getContainer()->set(EntityManagerInterface::class, $mockEm);

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Login')->form();
        $form['form[email]'] = 'test@example.com';
        $form['form[password]'] = 'password123';

        $client->submit($form);

        // Check for JavaScript alert presence (adapt as needed for your environment)
        $this->assertStringContainsString(
            'alert("There is No User Record Found");',
            $client->getResponse()->getContent()
        );
    }

    public function testFormSubmissionWrongPassword()
    {
        $client = static::createClient();

        // Mocking the EntityManager to return a user
        $mockUser = new User();
        $mockUser->setEmail('demirhan@gmail.com');
        $mockUser->setPassword('hashedpassword'); // Mock hashed password

        $mockRepository =  $this->createMock(\Doctrine\ORM\EntityRepository::class);
        $mockRepository->method('findOneBy')
            ->willReturn($mockUser); // User found

        $mockEm = $this->createMock(EntityManagerInterface::class);
        $mockEm->method('getRepository')
            ->willReturn($mockRepository);

        // Mocking the PasswordHasher to return false
        $mockPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $mockPasswordHasher->method('isPasswordValid')
            ->willReturn(false); // Password invalid

        // Replacing the real services with the mocks
        $client->getContainer()->set(EntityManagerInterface::class, $mockEm);
        $client->getContainer()->set(UserPasswordHasherInterface::class, $mockPasswordHasher);

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Login')->form();
        $form['form[email]'] = 'demirhan@gmail.com';
        $form['form[password]'] = 'hashedpassword';

        $client->submit($form);

        // Check for JavaScript alert presence (adapt as needed for your environment)
        $this->assertStringContainsString(
            'alert("Password is Wrong");',
            $client->getResponse()->getContent()
        );
    }


}