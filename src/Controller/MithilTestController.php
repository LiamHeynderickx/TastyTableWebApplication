<?php

namespace App\Controller;

use App\Entity\MithilTest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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


    #[Route('/rr',name:'SignIn')]
    public function register(Request $request, EntityManagerInterface $em): Response
    {
        $person=new User();


        $form = $this->createFormBuilder($person)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('username', TextType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Register'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password (you need to inject the password encoder)
            $encodedPassword =  $person->getPassword();
            $person->setPassword($encodedPassword);

            // Persist the user to the database
            $em->persist($person);
            $em->flush();

            $userId = $person->getId();
            // Redirect to a thank you page or login page
            return $this->redirectToRoute('logIn', ['id' => $userId]);
        }







        return $this->render('mithil_test/index.html.twig',[
            'form' => $form->createView()
        ]);
    }


}




