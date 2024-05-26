<?php

namespace App\Tests\EntityTests;

use App\Entity\Recipes;
use PHPUnit\Framework\TestCase;

class RecipesTest extends TestCase
{
    // Instantiate a recipe entity
    public function testRecipesInstantiation()
    {
        $recipe = new Recipes();
        $this->assertInstanceOf(Recipes::class, $recipe);
    }

    // Test creating a new user
    public function testSetAndGetUserId()
    {
        $recipe = new Recipes();
        $recipe->setUserId(1);
        $this->assertEquals(1, $recipe->getUserId());

        // Testing that entering a string as ID returns error
        $this->expectException(\TypeError::class);
        $recipe->setUserId('invalid');
    }

    // Testing getters and setters
    public function testSetAndGetRecipeName()
    {
        $recipe = new Recipes();
        $recipe->setRecipeName('Test Recipe');
        $this->assertEquals('Test Recipe', $recipe->getRecipeName());

        $this->expectException(\InvalidArgumentException::class);
        $recipe->setRecipeName(str_repeat('a', 101));
    }

    public function testSetAndGetRecipeDescription()
    {
        $recipe = new Recipes();
        $recipe->setRecipeDescription('This is a test recipe.');
        $this->assertEquals('This is a test recipe.', $recipe->getRecipeDescription());

        $this->expectException(\InvalidArgumentException::class);
        $recipe->setRecipeDescription(str_repeat('a', 256));
    }

    public function testSetAndGetPicturePath()
    {
        $recipe = new Recipes();
        $recipe->setPicturePath('path/to/image.jpg');
        $this->assertEquals('path/to/image.jpg', $recipe->getPicturePath());

        $this->expectException(\InvalidArgumentException::class);
        $recipe->setPicturePath(str_repeat('a', 256));
    }

    public function testSetAndGetCost()
    {
        $recipe = new Recipes();
        $recipe->setCost(100);
        $this->assertEquals(100, $recipe->getCost());

        // Testing input data type
        $this->expectException(\TypeError::class);
        $recipe->setCost('invalid');
    }

    public function testSetAndGetIngredients()
    {
        $recipe = new Recipes();
        $ingredients = ['Flour', 'Sugar', 'Eggs'];
        $recipe->setIngredients($ingredients);
        $this->assertEquals($ingredients, $recipe->getIngredients());
    }

    public function testSetAndGetIngredientsAmounts()
    {
        $recipe = new Recipes();
        $amounts = [200, 100, 3];
        $recipe->setIngredientsAmounts($amounts);
        $this->assertEquals($amounts, $recipe->getIngredientsAmounts());
    }

    public function testSetAndGetIngredientsUnits()
    {
        $recipe = new Recipes();
        $units = ['g', 'g', 'pcs'];
        $recipe->setIngredientsUnits($units);
        $this->assertEquals($units, $recipe->getIngredientsUnits());
    }

    public function testSetAndGetInstructions()
    {
        $recipe = new Recipes();
        $instructions = ['Mix ingredients', 'Bake for 30 minutes'];
        $recipe->setInstructions($instructions);
        $this->assertEquals($instructions, $recipe->getInstructions());
    }

    public function testSetAndGetTime()
    {
        $recipe = new Recipes();
        $recipe->setTime(45);
        $this->assertEquals(45, $recipe->getTime());

        $this->expectException(\TypeError::class);
        $recipe->setTime('invalid');
    }

    public function testSetAndGetCalories()
    {
        $recipe = new Recipes();
        $recipe->setCalories(250.5);
        $this->assertEquals(250.5, $recipe->getCalories());
    }

    public function testSetAndGetFat()
    {
        $recipe = new Recipes();
        $recipe->setFat(15.2);
        $this->assertEquals(15.2, $recipe->getFat());
    }

    public function testSetAndGetCarbs()
    {
        $recipe = new Recipes();
        $recipe->setCarbs(30.0);
        $this->assertEquals(30.0, $recipe->getCarbs());
    }

    public function testSetAndGetProtein()
    {
        $recipe = new Recipes();
        $recipe->setProtein(5.0);
        $this->assertEquals(5.0, $recipe->getProtein());
    }

    public function testSetAndGetServings()
    {
        $recipe = new Recipes();
        $recipe->setServings(4);
        $this->assertEquals(4, $recipe->getServings());

        $this->expectException(\TypeError::class);
        $recipe->setServings('invalid');
    }

    public function testSetAndGetDiet()
    {
        $recipe = new Recipes();
        $recipe->setDiet('Vegetarian');
        $this->assertEquals('Vegetarian', $recipe->getDiet());

        $this->expectException(\InvalidArgumentException::class);
        $recipe->setDiet(str_repeat('a', 101));
    }

    public function testSetAndGetType()
    {
        $recipe = new Recipes();
        $recipe->setType('Dessert');
        $this->assertEquals('Dessert', $recipe->getType());

        $this->expectException(\InvalidArgumentException::class);
        $recipe->setType(str_repeat('a', 101));
    }
}

