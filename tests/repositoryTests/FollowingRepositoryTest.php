<?php

namespace App\Tests\RepositoryTests;

use App\Entity\Following;
use App\Entity\User;
use App\Repository\FollowingRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FollowingRepositoryTest extends KernelTestCase
{
    private FollowingRepository $followingRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->followingRepository = static::getContainer()->get(FollowingRepository::class);
    }

    public function testEnvironment(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
    }

    public function testFindFollowingByUser(): void
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

        // Create and persist another user to follow
        $followingUser = new User();
        $followingUser->setUsername('followinguser');
        $followingUser->setEmail('followinguser@example.com');
        $followingUser->setPassword('password');
        $followingUser->setName('Following');
        $followingUser->setSurname('User');
        $entityManager->persist($followingUser);

        // Create and persist a following relationship
        $following = new Following();
        $following->setUserId($user);
        $following->setFollowingId($followingUser);
        $entityManager->persist($following);
        $entityManager->flush();

        // Find following by user
        $result = $this->followingRepository->findFollowingByUser($user->getId());
        $this->assertCount(1, $result);
        $this->assertSame($followingUser->getId(), $result[0]->getFollowingId()->getId());
    }

    public function testRemoveFollowingByUserAndFollowing(): void
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

        // Create and persist another user to follow
        $followingUser = new User();
        $followingUser->setUsername('followinguser');
        $followingUser->setEmail('followinguser@example.com');
        $followingUser->setPassword('password');
        $followingUser->setName('Following');
        $followingUser->setSurname('User');
        $entityManager->persist($followingUser);

        // Create and persist a following relationship
        $following = new Following();
        $following->setUserId($user);
        $following->setFollowingId($followingUser);
        $entityManager->persist($following);
        $entityManager->flush();

        // Remove the following relationship
        $this->followingRepository->removeFollowingByUserAndFollowing($user->getId(), $followingUser->getId());

        // Verify the relationship is removed
        $result = $this->followingRepository->findFollowingByUser($user->getId());
        $this->assertCount(0, $result);
    }
}
