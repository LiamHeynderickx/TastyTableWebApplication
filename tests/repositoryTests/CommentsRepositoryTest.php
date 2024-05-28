<?php

namespace App\Tests\RepositoryTests;

use App\Entity\Comments;
use App\Entity\User;
use App\Entity\Recipes;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentsRepositoryTest extends KernelTestCase
{
    private CommentsRepository $commentsRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->commentsRepository = static::getContainer()->get(CommentsRepository::class);
    }

    public function testEnvironment(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
    }

    public function testCommentsRepositoryInitialization(): void
    {
        $this->assertInstanceOf(CommentsRepository::class, $this->commentsRepository);

        // Create a new comment
        $comment = new Comments();

        // Check the data types of each field
        $this->assertNull($comment->getId(), 'Expected id to be null initially');
        $this->assertNull($comment->getUserId(), 'Expected userId to be null initially');
        $this->assertNull($comment->getRecipeId(), 'Expected recipeId to be null initially');
        $this->assertNull($comment->getComment(), 'Expected comment to be null initially');

        $comment->setUserId(new User());
        $comment->setRecipeId(new Recipes());
        $comment->setComment('This is a test comment.');

        $this->assertInstanceOf(User::class, $comment->getUserId(), 'Expected userId to be instance of User');
        $this->assertInstanceOf(Recipes::class, $comment->getRecipeId(), 'Expected recipeId to be instance of Recipes');
        $this->assertIsString($comment->getComment(), 'Expected comment to be a string');
    }

    public function testPreventSqlInjectionInComments(): void
    {
        // Create entities
        $user = new User();
        $user->setUsername('testuser4');
        $user->setEmail('testuser4@example.com');
        $user->setPassword('password'); // Set a dummy password
        $user->setName('Test');
        $user->setSurname('User');

        $recipe = new Recipes();
        $recipe->setRecipeName('testrecipe4');
        $recipe->setUserId(1); // Assuming a dummy user ID
        $recipe->setCost(100); // Set a dummy cost
        $recipe->setServings(2); // Set dummy servings
        $recipe->setDiet('Vegetarian'); // Set a dummy diet
        $recipe->setType('Main Course'); // Set a dummy type
        $recipe->setTime(30); // Set a dummy time

        // Create a new comment
        $comment = new Comments();
        $comment->setUserId($user);
        $comment->setRecipeId($recipe);
        $comment->setComment('This is a test comment.');

        // Persist the comment
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->persist($recipe);
        $entityManager->persist($comment);
        $entityManager->flush();

        // Ensure query builder prevents SQL injection
        $maliciousComment = 'This is a test comment; DROP TABLE users;';
        $queryBuilder = $this->commentsRepository->createQueryBuilder('c')
            ->where('c.comment = :comment')
            ->setParameter('comment', $maliciousComment);

        $query = $queryBuilder->getQuery();
        $safeResult = $query->getOneOrNullResult();

        // Validate the query result
        $this->assertNull($safeResult, 'SQL injection prevention failed for comment field');
    }
}
