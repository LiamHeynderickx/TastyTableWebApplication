<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Controller\TastyTableController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ShowUserProfileTest extends KernelTestCase
{
    private $entityManager;
    private $passwordHasher;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testShowUserProfileUserFound(): void
    {
        // Create a user in the database if it doesn't exist
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'testuser']);
        if (!$user) {
            $user = new User();
            $user->setUsername('testuser');
            $user->setEmail('testuser@example.com');
            $user->setName('Test'); // Set the name field
            $user->setSurname('User'); // Set the surname field
            $user->setDietPreference(User::DIET_VEGETARIAN); // Use the integer value for dietPreference
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123')); // Set a password

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        // Create the controller
        $controller = new TastyTableController();

        // Create a request
        $request = Request::create('/profile/testuser');

        // Call the controller method
        $response = $controller->showUserProfile($this->entityManager, 'testuser');

        // Assert the response is a Response object
        $this->assertInstanceOf(Response::class, $response);

        // Assert the response status code
        $this->assertEquals(200, $response->getStatusCode());

        // Assert the response content
        $this->assertStringContainsString('testuser', $response->getContent());
        $this->assertStringContainsString('vegetarian', $response->getContent());
    }

    public function testShowUserProfileUserNotFound(): void
    {
        // Ensure the user does not exist
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'nonexistentuser']);
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        // Create the controller
        $controller = new TastyTableController();

        // Expect an exception
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        // Call the controller method with a username that does not exist
        $controller->showUserProfile($this->entityManager, 'nonexistentuser');
    }
}
//yes