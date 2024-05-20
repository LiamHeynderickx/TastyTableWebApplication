<?php

namespace App\Controller;

use App\Entity\MithilTest;

use App\Entity\Recipes;
use App\Entity\SavedRecipes;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\SpoonacularApiService;

class TastyTableController extends AbstractController
{
    // Route for displaying the form

    // Route for handling form submission
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em, SessionInterface $session,LoggerInterface $logger,UserPasswordHasherInterface $passwordHasher): Response
    {
        $alertMessage = null;
        $person = new User();
        $form = $this->createFormBuilder($person)
            ->add('email', EmailType::class, [
                'attr' => ['id' => 'nameField', 'placeholder' => 'Email']
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['id' => 'passwordField', 'placeholder' => 'Password']
            ])
            ->add('login', SubmitType::class, [
                'label' => 'Login',
                'attr' => ['id' => 'loginBtn', 'class' => 'btn-field']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $person->getEmail();
            $password = $person->getPassword();



            // Retrieve user by email
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$user)
            {
                //No user is found

                $alertMessage='There is No User Record Found';

            }

            elseif  (!$passwordHasher->isPasswordValid($user, $password)) {
                // password is wrong

                $alertMessage='Password is Wrong';

            }
            else{
               //Correct login
                //if user logged in
                //then configure session parameters
                //!!!!!!If you have additional parameters add them !!!!!!!
                $session->set('isOnline', true);
                $session->set('username', $user->getUsername());
                $session->set('mail', $user->getEmail());

                $session->set('userId', $user->getId());

      //          $logger->info('User logged in', [
        //            'userId' => $user->getId(),
          //          'username' => $user->getUsername(),
            //        'email' => $user->getEmail(),
              //  ]);
                return $this->redirectToRoute('homePage');
            }
        }

        return $this->render('Pages/index.html.twig', [
            'form' => $form->createView(),
            'alertMessage' => $alertMessage
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, EntityManagerInterface $em, SessionInterface $session,UserPasswordHasherInterface $passwordHasher): Response
    {
        $person = new User();


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

        //check if form is valid (filled in or not)
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password (you need to inject the password encoder)
            $encodedPassword = $passwordHasher->hashPassword($person, $person->getPassword());

            $person->setPassword($encodedPassword);

            // Persist the user to the database

            try {
                // Persist the new user entity to the database
                $em->persist($person);
                $em->flush();

                // Redirect to a different page upon successful registration
                $session->set('isOnline', true);
                $session->set('username', $person->getUsername());
                $session->set('mail', $person->getEmail());
                $session->set('userId', $person->getId());

                // Redirect to a thank you page or login page
                return $this->redirectToRoute('homePage');
                //return $this->redirectToRoute('login');
            } catch (\Exception $e) {
                // Add an error message to the session flash bag
                //TODOO ADD ALERT
                $session->getFlashBag()->add('error', 'There was an error registering your account. Please try again.');
            }


        }

        return $this->render('Pages/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/LogOut', name: 'logOut')]
    public function LogOut(Request $request, EntityManagerInterface $em, SessionInterface $session,UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createFormBuilder()->getForm();
        $session->clear();
        return $this->render('Pages/homePage.html.twig', [
            'form' => $form->createView()
        ]);
    }

/*

    #[Route('/search-recipes', name: 'search_recipes')]
    public function searchRecipes(Request $request, SpoonacularApiService $apiService): Response
    {
        $params = [
            'minCarbs' => $request->query->get('minCarbs', 10),
            'maxCarbs' => $request->query->get('maxCarbs', 50),
            'minProtein' => $request->query->get('minProtein', 10),
            'maxProtein' => $request->query->get('maxProtein', 50),
            'minFat' => $request->query->get('minFat', 10),
            'maxFat' => $request->query->get('maxFat', 50)
        ];

        try {
            $recipes = $apiService->searchRecipesByNutrients($params);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage());
        }

        return $this->render('Pages/search.html.twig', [
            'recipes' => $recipes
        ]);
    }
    */

    #[Route('/homePage', name: 'homePage')]
    public function homePage(Request $request, EntityManagerInterface $em, SessionInterface $session,SpoonacularApiService $apiService): Response
    {

        // Get filter criteria from the request
        $filters = [
            'vegetarian' => $request->query->get('vegetarian'),
            'vegan' => $request->query->get('vegan'),
            'gluten-free' => $request->query->get('gluten-free'),
            'dairy-free' => $request->query->get('dairy-free')
        ];

        $form = $this->createFormBuilder()->getForm();

        $recipes = array();
        for ($x = 0; $x < 1; $x++) { //change loop limit to change number of recipes displayed in home
            //display less recipes to save key usage for testing
            $recipes[] = $apiService->getRandomRecipe($filters);
        }

//        $recipeID = new Integer();
//
//        $form = $this->createFormBuilder($recipeID)
//            ->add('recipeID', TextType::class, [
//                'attr' => ['id' => 'id', 'placeholder' => 'recipeID']
//            ])
//            ->getForm();
//        $form->handleRequest($request);

        return $this->render('Pages/homePage.html.twig', [
            'form' => $form->createView(),
            'recipes' => $recipes,
            'filters' => $filters
        ]);
    }

    #[Route('/recipe/{id}', name: 'recipeDisplayer')]
    public function recipeDisplayer($id, SpoonacularApiService $apiService): Response
    {
        // Fetch the recipe details using the API service
        $recipeDetails = $apiService->getRecipeById($id);

        return $this->render('Pages/recipeDisplayer.html.twig', [
            'recipe' => $recipeDetails,
        ]);
    }


    #[Route('/aboutUs', name: 'aboutUs')]
    public function aboutUs(Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createFormBuilder()->getForm();

        return $this->render('Pages/AboutUs.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function getSavedRecipes(Request $request, EntityManagerInterface $em,SessionInterface $session,SpoonacularApiService $apiService): Response
    {
        if (!$session->get('isOnline'))
        {
            return $this->redirectToRoute('index');
        }


        $dietaryPreferences = [
            'none' => 'None',
            'lacto-vegetarian' => 'Lacto Vegetarian',
            'ovo-vegetarian' => 'Ovo Vegetarian',
            'ovolacto-vegetarian' => 'Ovo-Lacto Vegetarian',
            'pescatarian' => 'Pescatarian',
            'vegan' => 'Vegan',
            'vegetarian' => 'Vegetarian'
        ];


        $selectedDiets = $request->query->all('diets');
        $type = $request->query->get('type');
        $UserID=$session->get('userId');


        //if it is saved recipies
        if ($type === 'saved') {
            // Fetch saved recipes from the API & DB
            $recipeIds = $em->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($UserID, 1,0);
            if (!empty($recipeIds)) {
                // return new Response('No saved recipes found.');


                //From the API
                try {
                    $recipes = $apiService->getRecipesInformationBulk($recipeIds);
                } catch (\Exception $e) {
                    return new Response('Error: ' . $e->getMessage());
                }
                //Add Here DB recipies part
                //concatenate them together

                return $this->render('Pages/Profile.html.twig', [
                    'dietaryPreferences' => $dietaryPreferences,
                    'selectedDiets' => $selectedDiets,
                    'recipes' => $recipes
                ]);
            }

        }elseif ($type === 'my'){
            // Fetch my recipes from the DB and set is my TRUE
            $recipeIds = $em->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($UserID, 1,1);
            if (!empty($recipeIds)) {
                // return new Response('No saved recipes found.');

                try {
                    $recipes = $apiService->getRecipesInformationBulk($recipeIds);
                } catch (\Exception $e) {
                    return new Response('Error: ' . $e->getMessage());
                }
                return $this->render('Pages/Profile.html.twig', [
                    'dietaryPreferences' => $dietaryPreferences,
                    'selectedDiets' => $selectedDiets,
                    'recipes' => $recipes

                ]);
            }
        }
        else{
            $recipeIds = $em->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($UserID, 1,0);
            if (!empty($recipeIds)) {
                // return new Response('No saved recipes found.');

                try {
                    $recipes = $apiService->getRecipesInformationBulk($recipeIds);
                } catch (\Exception $e) {
                    return new Response('Error: ' . $e->getMessage());
                }
                return $this->render('Pages/Profile.html.twig', [
                    'dietaryPreferences' => $dietaryPreferences,
                    'selectedDiets' => $selectedDiets,
                    'recipes' => $recipes
                ]);
            }

        }
        return $this->render('Pages/Profile.html.twig', [
            'dietaryPreferences' => $dietaryPreferences,
            'selectedDiets' => $selectedDiets,
            'recipes' => []

        ]);
    }

    #[Route('/recipeSubmission', name: 'recipeSubmission')]
    public function recipeSubmission(Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $recipe = new Recipes();

        $recipe->setUserId(1);


        $form = $this->createFormBuilder($recipe)
            ->add('recipeName', TextType::class, ['label' => 'Recipe Name'])
            ->add('recipeDescription', TextareaType::class, ['label' => 'Recipe Description', 'required' => false])
            ->add('picture', FileType::class, ['label' => 'Recipe Image', 'required' => false])
            ->add('cost', ChoiceType::class, [
                'choices' => ['€' => '1', '€€' => '2', '€€€' => '3'],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'form-label-cost'],
            ])
            ->add('time', IntegerType::class, ['label' => 'Cooking Time (minutes)'])
            ->add('calories', IntegerType::class, ['label' => 'Calories', 'required' => false])
            ->add('fat', IntegerType::class, ['label' => 'Fat', 'required' => false])
            ->add('carbs', IntegerType::class, ['label' => 'Carbohydrates', 'required' => false])
            ->add('protein', IntegerType::class, ['label' => 'Protein', 'required' => false])
            ->add('servings', IntegerType::class, ['label' => 'Number of Servings'])
            ->add('diet', TextType::class, ['label' => 'Diet'])
            ->add('type', TextType::class, ['label' => 'Meal Type'])
            ->add('ingredients', HiddenType::class, ['mapped' => false])
            ->add('ingredientsAmounts', HiddenType::class, ['mapped' => false])
            ->add('ingredientsUnits', HiddenType::class, ['mapped' => false])
            ->add('instructions', HiddenType::class, ['mapped' => false])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Handle file upload
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
                try {
                    $pictureFile->move($this->getParameter('recipes_images_directory'), $newFilename);
                } catch (FileException $e) {
                    // Handle file upload error
                }
                $recipe->setPicture($newFilename);
            }

            // Get ingredients, quantities, units, and instructions from hidden fields
            $ingredients = json_decode($form->get('ingredients')->getData(), true);
            $ingredientsAmounts = json_decode($form->get('ingredientsAmounts')->getData(), true);
            $ingredientsUnits = json_decode($form->get('ingredientsUnits')->getData(), true);
            $instructions = json_decode($form->get('instructions')->getData(), true);

            // Set the data to the recipe entity
            if ($ingredients != null) {
                $recipe->setIngredients($ingredients);
            }
            if ($ingredientsAmounts != null) {
                $recipe->setIngredientsAmounts($ingredientsAmounts);
            }
            if ($instructions != null) {
                $recipe->setIngredientsUnits($ingredientsUnits);
            }
            if ($ingredients != null) {
                $recipe->setInstructions($instructions);
            }

//            // Log raw request data
//            $logger->info('Raw request data:', $request->request->all());
//
//            // Log decoded arrays
//            $logger->info('Decoded ingredients:', $ingredients);
//            $logger->info('Decoded ingredientsAmounts:', $ingredientsAmounts);
//            $logger->info('Decoded ingredientsUnits:', $ingredientsUnits);
//            $logger->info('Decoded instructions:', $instructions);

            // Save the recipe to the database
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('homePage');
        }

        return $this->render('Pages/recipeSubmission.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recipeDisplay', name: 'recipeDisplay')]
    public function display(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $recipe = $em->getRepository(Recipes::class)->find(7);

        if (!$recipe) {
            throw $this->createNotFoundException('The recipe does not exist');
        }

        return $this->render('Pages/recipeDisplay.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}

