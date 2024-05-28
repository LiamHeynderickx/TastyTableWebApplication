<?php

namespace App\Tests\repositoryTests;

use App\Entity\Followers;
use App\Entity\User;
use App\Repository\FollowersRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FollowersRepositoryTest extends KernelTestCase
{
    private FollowersRepository $followersRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->followersRepository = static::getContainer()->get(FollowersRepository::class);
    }

    public function testEnvironment(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
    }

    public function testFindFollowersByUser(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        // Create and persist a user
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword('password');
        $user->setName('Test');
        $user->setSurname('User');
        $entityManager->persist($user);

        // Create and persist a follower
        $follower = new User();
        $follower->setUsername('follower');
        $follower->setEmail('follower@example.com');
        $follower->setPassword('password');
        $follower->setName('Follower');
        $follower->setSurname('User');
        $entityManager->persist($follower);

        // Create and persist a follower relationship
        $followers = new Followers();
        $followers->setUserId($user);
        $followers->setFollowerId($follower);
        $entityManager->persist($followers);
        $entityManager->flush();

        // Find followers by user
        $result = $this->followersRepository->findFollowersByUser($user->getId());
        $this->assertCount(1, $result);
        $this->assertSame($follower->getId(), $result[0]->getFollowerId()->getId());
    }

    public function testRemoveFollowingByUserAndFollowers(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        // Create and persist a user
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword('password');
        $user->setName('Test');
        $user->setSurname('User');
        $entityManager->persist($user);

        // Create and persist a follower
        $follower = new User();
        $follower->setUsername('follower');
        $follower->setEmail('follower@example.com');
        $follower->setPassword('password');
        $follower->setName('Follower');
        $follower->setSurname('User');
        $entityManager->persist($follower);

        // Create and persist a follower relationship
        $followers = new Followers();
        $followers->setUserId($user);
        $followers->setFollowerId($follower);
        $entityManager->persist($followers);
        $entityManager->flush();

        // Remove the following relationship
        $this->followersRepository->removeFollowingByUserAndFollowers($user->getId(), $follower->getId());

        // Verify the relationship is removed
        $result = $this->followersRepository->findFollowersByUser($user->getId());
        $this->assertCount(0, $result);
    }
}
