<?php

namespace App\Tests\EntityTests;

use App\Entity\Followers;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class FollowersTest extends TestCase
{
    // Instantiate a Followers entity
    public function testFollowersInstantiation()
    {
        $followers = new Followers();
        $this->assertInstanceOf(Followers::class, $followers);
    }
    //we do not test setting and getting ID of Followers because ORM takes care of it, ID will be null until we persist it into db

    // Test setting and getting the userId
    public function testSetAndGetUserId()
    {
        $followers = new Followers();
        $user = new User();
        $followers->setUserId($user);
        $this->assertSame($user, $followers->getUserId());

        // Testing that entering a non-User object returns error
        $this->expectException(\TypeError::class);
        $followers->setUserId('invalid');
    }

    // Test setting and getting the followerId
    public function testSetAndGetFollowerId()
    {
        $followers = new Followers();
        $user = new User();
        $followers->setFollowerId($user);
        $this->assertSame($user, $followers->getFollowerId());

        // Testing that entering a non-User object returns error
        $this->expectException(\TypeError::class);
        $followers->setFollowerId('invalid');
    }
}
