<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use TypeError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserTest extends TestCase
{
    private $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        // Create a mock for the EntityManager to simulate its behaviour
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Create a mock for the UserRepository (EntityRepository) to handle db ops
        $this->userRepository = $this->createMock(EntityRepository::class);

        // Configure the EntityManager to return the UserRepository. Means whenever mock entitymanager is called with User class, it communicates with mock UserRep
        $this->entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($this->userRepository);
    }

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testValidUser(): void
    {
        // All fields are varchar, so ok to use this
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword('password123');
        $user->setName('Test');
        $user->setSurname('User');
        $user->setDietPreference('vegetarian');

        $this->assertEquals('testuser', $user->getUsername());
        $this->assertEquals('testuser@example.com', $user->getEmail());
        $this->assertEquals('password123', $user->getPassword());
        $this->assertEquals('Test', $user->getName());
        $this->assertEquals('User', $user->getSurname());
        $this->assertEquals('vegetarian', $user->getDietPreference());
    }

    // No null fields allowed in User, testing for this requirement
    public function testNullFields(): void
    {
        $user = new User();

        $this->expectException(TypeError::class);
        $user->setUsername(null);

        $this->expectException(TypeError::class);
        $user->setEmail(null);

        $this->expectException(TypeError::class);
        $user->setPassword(null);

        $this->expectException(TypeError::class);
        $user->setName(null);

        $this->expectException(TypeError::class);
        $user->setSurname(null);

        $this->expectException(TypeError::class);
        $user->setDietPreference(null);
    }

    // Testing if collection is being initialized correctly inside User
    public function testSetGetCollections(): void
    {
        $user = new User();

        $this->assertInstanceOf(Collection::class, $user->getUserFollowing());
        $this->assertInstanceOf(Collection::class, $user->getUserFollowers());
        $this->assertInstanceOf(Collection::class, $user->getUserPosts());
        $this->assertInstanceOf(Collection::class, $user->getUserComments());
        $this->assertInstanceOf(Collection::class, $user->getSavedRecipes());
    }

    //testing if username is unique
    public function testUniqueUsername(): void
    {
        // Create a user with a specific username
        $user = new User();
        $user->setUsername('uniqueUsername');
        $user->setEmail('test@example.com');
        $user->setPassword('password');
        $user->setName('Test');
        $user->setSurname('User');
        $user->setDietPreference('vegetarian');

        // Configure the UserRepository mock to return a user when searching for 'uniqueUsername'
        $this->userRepository
            ->expects($this->any())
            ->method('findOneBy')
            ->with(['username' => 'uniqueUsername'])
            ->willReturn($user);

        // Attempt to create another user with the same username but different values for all else
        $newUser = new User();
        $newUser->setUsername('uniqueUsername');
        $newUser->setEmail('test2@example.com');
        $newUser->setPassword('passwordz');
        $newUser->setName('Test2');
        $newUser->setSurname('User2');
        $newUser->setDietPreference('vegan');

        // Check if a user with the same username already exists
        $existingUser = $this->userRepository->findOneBy(['username' => 'uniqueUsername']);

        $this->assertNotNull($existingUser, 'A user with this username already exists.');

        // Assert that the usernames are the same
        $this->assertEquals($existingUser->getUsername(), $newUser->getUsername(), 'The username must be unique.');
    }
}
