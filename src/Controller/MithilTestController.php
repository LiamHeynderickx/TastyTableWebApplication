<?php

namespace App\Controller;

use App\Entity\MithilTest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MithilTestController extends AbstractController
{
    // Route for displaying the form
    #[Route('/', name: 'app_', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('mithil_test/index.html.twig');
    }

    // Route for handling form submission
    #[Route('/', name: 'app_create', methods: ['POST'])]
    public function createEntry(Request $request, EntityManagerInterface $entityManager): Response
    {
        $col1 = $request->request->get('col1'); // Get the col1 value from the form

        $entry = new MithilTest();
        $entry->setCol1($col1);

        // Persist the new entry
        $entityManager->persist($entry);
        $entityManager->flush();

        return new Response('Saved new entry with col1: '.$entry->getCol1());
    }
}
