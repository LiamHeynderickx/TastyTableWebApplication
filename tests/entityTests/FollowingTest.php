<?php

namespace App\Tests\EntityTests;

use App\Entity\Following;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class FollowingTest extends TestCase
{
    // Instantiate a Following entity
    public function testFollowingInstantiation()
    {
        $following = new Following();
        $this->assertInstanceOf(Following::class, $following);
    }

    // Test setting and getting the userId
    public function testSetAndGetUserId()
    {
        $following = new Following();
        $user = new User();
        $following->setUserId($user);
        $this->assertSame($user, $following->getUserId());

        // Testing that entering a non-User object returns error
        $this->expectException(\TypeError::class);
        $following->setUserId('invalid');
    }

    // Test setting and getting the followingId
    public function testSetAndGetFollowingId()
    {
        $following = new Following();
        $user = new User();
        $following->setFollowingId($user);
        $this->assertSame($user, $following->getFollowingId());

        // Testing that entering a non-User object returns error
        $this->expectException(\TypeError::class);
        $following->setFollowingId('invalid');
    }
}
