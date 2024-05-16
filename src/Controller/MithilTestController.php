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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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


    #[Route('/register',name:'SignIn')]
    public function register(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher): Response
    {
        $person=new User();


        $form = $this->createFormBuilder($person)
            ->add('username', TextType::class, [
                'attr' => ['id' => 'username', 'placeholder' => 'Username']
            ])
            ->add('name', TextType::class, [
                'attr' => ['id' => 'name', 'placeholder' => 'Name']
            ])
            ->add('surname', TextType::class, [
                'attr' => ['id' => 'surname', 'placeholder' => 'Surname']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['id' => 'nameField', 'placeholder' => 'Email']
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['id' => 'passwordField', 'placeholder' => 'Password']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['id' => 'register', 'class' => 'btn-field']
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password (you need to inject the password encoder)
            $encodedPassword = $passwordHasher->hashPassword($person, $person->getPassword());

            $person->setPassword($encodedPassword);

            // Persist the user to the database
            $em->persist($person);
            $em->flush();

            $userId = $person->getId();
            // Redirect to a thank you page or login page
            return $this->redirectToRoute('logIn', ['id' => $userId]);
        }

        return $this->render('Pages/register.html.twig',[
            'form' => $form->createView()
        ]);
    }

/*
    #[Route('/login',name:'logIn')]
    public function login(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher): Response
    {
        $person=new User();


        $form = $this->createFormBuilder($person)
            ->add('username', TextType::class, [
                'attr' => ['id' => 'username', 'placeholder' => 'Username']
            ])
            ->add('name', TextType::class, [
                'attr' => ['id' => 'name', 'placeholder' => 'Name']
            ])
            ->add('surname', TextType::class, [
                'attr' => ['id' => 'surname', 'placeholder' => 'Surname']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['id' => 'nameField', 'placeholder' => 'Email']
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['id' => 'passwordField', 'placeholder' => 'Password']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['id' => 'register', 'class' => 'btn-field']
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password (you need to inject the password encoder)
            $encodedPassword = $passwordHasher->hashPassword($person, $person->getPassword());
            $person->setPassword($encodedPassword);

            // Persist the user to the database
            $em->persist($person);
            $em->flush();

            $userId = $person->getId();
            // Redirect to a thank you page or login page
            return $this->redirectToRoute('logIn', ['id' => $userId]);
        }

        return $this->render('Pages/lo.html.twig',[
            'form' => $form->createView()
        ]);
    }
*/
}




