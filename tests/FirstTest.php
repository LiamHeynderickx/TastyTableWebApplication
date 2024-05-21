<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Controller\TastyTableController;

class YourClassTest extends TestCase
{
    public function testGetWelcome(): void
    {
        // Instantiate the class containing the getWelcome method
        $Controller = new TastyTableController();

        // Call the getWelcome method
        $result = $Controller->getWelcome();

        // Assert that the method returns the expected string
        $this->assertEquals('Welcome', $result);
    }
}
