<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetSavedRecipesTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
    }

    public function testIfSavedRecipesNull(): void
    {

    }
}
