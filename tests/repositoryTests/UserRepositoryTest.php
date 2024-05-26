<?php

namespace App\Tests\repositoryTests;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    public function testEnvironment(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
    }

    public function testUserRepository(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $this->assertInstanceOf(UserRepository::class, $userRepository);
    }

    public function testFindUserById(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find(1); // Assuming user with ID 1 exists in the test database
        //assert that there is an instance of this entry
        $this->assertInstanceOf(User::class, $user);
    }

    public function testFindOneBy(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['id' => 1]); // Assuming user with ID 1 exists in the test database
        $this->assertInstanceOf(User::class, $user);
    }

    public function testFindAll(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();
        $this->assertIsArray($users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

    public function testFindBy(): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findBy(['id' => 1]); // Assuming user with ID 1 exists in the test database
        $this->assertIsArray($users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

//    public function testRetrieveUserData(): void
//    {
//        $userRepository = $this->entityManager->getRepository(User::class);
//        $result = $userRepository->retrieveUserData(1); // Assuming user with ID 1 exists in the test database
//        $this->assertIsArray($result);
//        $this->assertNotEmpty($result);
//    }
}
