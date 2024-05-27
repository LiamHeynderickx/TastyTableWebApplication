<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\MithilTest;

use App\Entity\Recipes;
use App\Entity\SavedRecipes;
use App\Entity\User;
use App\Entity\Followers;
use App\Entity\Following;
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;


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
                $session->set('type', 'saved');

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
                'attr' => ['id' => 'username', 'placeholder' => 'Username'],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]*$/',
                        'message' => 'Username must not contain special characters.',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => ['id' => 'name', 'placeholder' => 'Name'],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]*$/',
                        'message' => 'Name must not contain special characters.',
                    ]),
                ],
            ])
            ->add('surname', TextType::class, [
                'attr' => ['id' => 'surname', 'placeholder' => 'Surname'],
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]*$/',
                        'message' => 'Surname must not contain special characters.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['id' => 'nameField', 'placeholder' => 'Email'],
                'constraints' => [
                    new NotBlank(),
                    new Email([
                        'message' => 'The email "{{ value }}" is not a valid email.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['id' => 'passwordField', 'placeholder' => 'Password'],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['id' => 'register', 'class' => 'btn-field'],
            ])
            ->getForm();

        $form->handleRequest($request);
        $alertMessage=null;
        //check if form is valid (filled in or not)
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password (you need to inject the password encoder)
            $existingUser = $em->getRepository(User::class)->findOneBy([
                'username' => $person->getUsername()
            ]);
            $existingEmail = $em->getRepository(User::class)->findOneBy([
                'email' => $person->getEmail()
            ]);
            if ($existingUser) {
                //$session->getFlashBag()->add('error', 'Username already taken. Please choose another.');
                $alertMessage='Username already taken. Please choose another.';
                return $this->render('Pages/register.html.twig', ['form' => $form->createView()
                    ,'alertMessage'=>$alertMessage]);
            }
            if ($existingEmail) {
                //$session->getFlashBag()->add('error', 'Email already registered. Please use another email or log in.');
                $alertMessage='Email already registered. Please use another email or log in.';
                return $this->render('Pages/register.html.twig', ['form' => $form->createView()
                    ,'alertMessage'=>$alertMessage]);
            }
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
                $alertMessage='There was an error registering your account. Please try again.';
                return $this->render('Pages/register.html.twig', ['form' => $form->createView()
                    ,'alertMessage'=>$alertMessage]);
                //  $session->getFlashBag()->add('error', 'There was an error registering your account. Please try again.');
            }


        }

        return $this->render('Pages/register.html.twig', [
            'form' => $form->createView(),
            'alertMessage'=>$alertMessage,

        ]);
    }
    #[Route('/LogOut', name: 'logOut')]
    public function LogOut(Request $request, EntityManagerInterface $em, SessionInterface $session,UserPasswordHasherInterface $passwordHasher): Response
    {

        $session->clear();
        return $this->redirectToRoute('index');

    }

    #[Route('/homePage', name: 'homePage')]
    public function homePage(Request $request, EntityManagerInterface $em): Response
    {

        // Get filter criteria from the request
        $filters = [
            'vegetarian' => $request->query->get('vegetarian'),
            'vegan' => $request->query->get('vegan'),
            'gluten-free' => $request->query->get('gluten-free'),
            'dairy-free' => $request->query->get('dairy-free')
        ];

        $form = $this->createFormBuilder()->getForm();

        $recipes = array(); //populate this with database

        $recipe = new Recipes();

        $allRecipes = $em->getRepository(Recipes::class)->findAll();

        // Randomly select 9 recipes
        $recipes = [];
        if (count($allRecipes) > 9) {
            $randomKeys = array_rand($allRecipes, 9);
            foreach ($randomKeys as $key) {
                $recipes[] = $allRecipes[$key];
            }
        } else {
            // If there are less than or equal to 9 recipes, use all of them
            $recipes = $allRecipes;
        }

        return $this->render('Pages/homePage.html.twig', [
            'form' => $form->createView(),
            'recipes' => $recipes,
            'filters' => $filters
        ]);
    }

    #[Route('/homePageFiltered', name: 'homePageFiltered')]
    public function homePageFiltered(Request $request, EntityManagerInterface $em): Response
    {

        // Get filter criteria from the request
        $filters = [
            'vegetarian' => $request->query->get('vegetarian'),
            'vegan' => $request->query->get('vegan'),
            'gluten-free' => $request->query->get('gluten-free'),
            'dairy-free' => $request->query->get('dairy-free')
        ];

        $form = $this->createFormBuilder()->getForm();

        $allRecipes = $em->getRepository(Recipes::class)->getFilteredRecipes($filters);

        // Randomly select 9 recipes
        $recipes = [];
        if (count($allRecipes) > 9) {
            $randomKeys = array_rand($allRecipes, 9);
            foreach ($randomKeys as $key) {
                $recipes[] = $allRecipes[$key];
            }
        } else {
            // If there are less than or equal to 9 recipes, use all of them
            $recipes = $allRecipes;
        }

        return $this->render('Pages/homePage.html.twig', [
            'form' => $form->createView(),
            'recipes' => $recipes,
            'filters' => $filters
        ]);
    }

    #[Route('/homePageAPI', name: 'homePageAPI')]
    public function homePageAPI(Request $request, SpoonacularApiService $apiService): Response
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

        return $this->render('Pages/homePageAPI.html.twig', [
            'form' => $form->createView(),
            'recipes' => $recipes,
            'filters' => $filters
        ]);
    }

    #[Route('/searchAPI', name: 'searchAPI')]
    public function searchRecipesAPI(Request $request, SpoonacularApiService $apiService): Response
    {
        $query = $request->query->get('query');

        $filters = [
            'vegetarian' => $request->query->get('vegetarian'),
            'vegan' => $request->query->get('vegan'),
            'gluten-free' => $request->query->get('gluten-free'),
            'dairy-free' => $request->query->get('dairy-free')
        ];

        // Fetch recipes based on the search query
        $form = $this->createFormBuilder()->getForm();


        $recipes = $apiService->searchRecipesByName($query);


        return $this->render('Pages/homePageAPI.html.twig', [
            'form' => $form->createView(),
            'recipes' => $recipes,
            'filters' => $filters
        ]);
    }

    #[Route('/searchDB', name: 'searchDB')]
    public function searchRecipesDB(Request $request, EntityManagerInterface $em): Response
    {
        $query = $request->query->get('query');

        $filters = [
            'vegetarian' => $request->query->get('vegetarian'),
            'vegan' => $request->query->get('vegan'),
            'gluten-free' => $request->query->get('gluten-free'),
            'dairy-free' => $request->query->get('dairy-free')
        ];

        // Fetch recipes based on the search query
        $form = $this->createFormBuilder()->getForm();

        $allRecipes = $em->getRepository(Recipes::class)->findRecipesByName($query);

        return $this->render('Pages/homePage.html.twig', [
            'form' => $form->createView(),
            'recipes' => $allRecipes,
            'filters' => $filters
        ]);
    }

    #[Route('/recipe/{id}', name: 'recipeDisplayAPI')] //not used rn
    public function recipeDisplayer($id, SpoonacularApiService $apiService): Response
    {
        // Fetch the recipe details using the API service
        $recipeDetails = $apiService->getRecipeById($id);

        return $this->render('Pages/recipeDisplay.html.twig', [
            'recipe' => $recipeDetails,
        ]);
    }

    #[Route('/follows', name: 'follows')]
    public function follows(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->get('isOnline')) {
            return $this->redirectToRoute('index');
        }

        $user = $em->getRepository(User::class)->findOneBy(['username' => $session->get('username')]);

        $userId = $session->get('userId');
        $type = $request->query->get('type');

        if ($type === 'followers') {
            $followers = $em->getRepository(Followers::class)->findBy(['userId' => $userId]);
            $followData = array_map(fn($follower) => $follower->getFollowerId(), $followers);
        } elseif ($type === 'following') {
            $following = $em->getRepository(Following::class)->findBy(['userId' => $userId]);
            $followData = array_map(fn($following) => $following->getFollowingId(), $following);
        } else {
            $followData = [];
        }

        return $this->render('Pages/Follows.html.twig', [
            'follows' => $followData,
            'type' => $type,
            'user' => $user
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
    public function getSavedRecipes(Request $request, EntityManagerInterface $em,SessionInterface $session,LoggerInterface $logger,SpoonacularApiService $apiService): Response
    {
        if (!$session->get('isOnline'))
        {
            return $this->redirectToRoute('index');
        }

        $alertInfo=null;

        $user = $em->getRepository(User::class)->findOneBy(['username' => $session->get('username')]);

        $dietaryPreferences = [
            'lacto ovo vegetarian' => 'lacto ovo vegetarian',
            'lacto-vegetarian' => 'Lacto Vegetarian',
            'ovo-vegetarian' => 'Ovo Vegetarian',
            'ovolacto-vegetarian' => 'Ovo-Lacto Vegetarian',
            'pescatarian' => 'Pescatarian',
            'vegan' => 'Vegan',
            'vegetarian' => 'Vegetarian'
        ];


        $selectedDiets = $request->query->all('diets');
        $diets = !empty($selectedDiets) ? $selectedDiets : [];

        // $logger->info('Selected diets:', ['diets' => $diets]);
        $type = $request->query->get('type');




        $UserID=$session->get('userId');

        //!!!!Validation !!!!!!!!!
        $userID = $session->get('userId');
        if (empty($userID)) {
            // Handle invalid or missing userID
            $logger->error('Missing userID in session.');
            return $this->redirectToRoute('login');
        }

        if ($type!=='saved' and $type !== 'my')
        {
            $type= $session->get('type');
        }
        else
        {
            $session->set('type',$type);
        }


        //if it is saved recipies
        if ($type === 'saved') {
            // Fetch saved recipes from the API & DB
            $recipeIds = $em->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($UserID, 1,0);
            // echo "Fetched Recipe IDs:\n";
            //print_r($recipeIds);
            $ApiRecipes=[];
            if (!empty($recipeIds)) {
                // return new Response('No saved recipes found.');
                //From the API
                $filteredArrays=[];
                try {
                    $ApiRecipes = $apiService->getRecipesInformationBulk($recipeIds);

                    foreach ($ApiRecipes as $apiRecipe){

                        if (!empty($selectedDiets)){


                            if (array_intersect($apiRecipe['diets'], $selectedDiets))
                            {

                                $filteredArrays []= $apiRecipe;
                                //  print_r( $filteredArrays);

                            }
                        }

                        if (!empty($selectedDiets))
                        {
                            $ApiRecipes=$filteredArrays;
                        }

                    }

                } catch (\Exception $e) {
                    //return new Response('Error: ' . $e->getMessage());
                    return $this->render('Pages/Profile.html.twig', [
                        'dietaryPreferences' => $dietaryPreferences,
                        'selectedDiets' => $selectedDiets,
                        'API_recipes' => $ApiRecipes,
                        'Db_recipes'=>[],
                        'user' => $user,
                        'alert'=>'API Request failed']);
                }
            }

            $recipeIds = $em->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($UserID, 0,0);
            //$DbRecipes = $em->getRepository(Recipes::class)->findBy(['id' => $recipeIds]);
            if (empty($recipeIds)) {
                // $logger->info('No recipes found for user.', ['userID' => $userID]);

                $alertInfo='No recipes found for user.';
            }
            $DbRecipes = $em->getRepository(Recipes::class)->findRecipesByIdsAndDiets($recipeIds, $diets);



            return $this->render('Pages/Profile.html.twig', [
                'dietaryPreferences' => $dietaryPreferences,
                'selectedDiets' => $selectedDiets,
                'API_recipes' => $ApiRecipes,
                'Db_recipes'=>$DbRecipes,
                'user' => $user,
                'alert'=>$alertInfo
            ]);

        }elseif ($type === 'my'){
            // Fetch my recipes from the DB and set is my TRUE
            $recipeIds = $em->getRepository(Recipes::class)->findIdsByUserId($UserID);


            //$DbRecipes = $em->getRepository(Recipes::class)->findBy(['id' => $recipeIds]);
            $DbRecipes = $em->getRepository(Recipes::class)->findRecipesByIdsAndDiets($recipeIds, $diets);

            if (empty($DbRecipes) && empty($ApiRecipes)) {
                //$logger->info('No recipes available to display.', ['userID' => $userID]);
                //$this->addFlash('notice', 'No recipes available.');
                $alertInfo='No recipes available to display.';
            }

            return $this->render('Pages/Profile.html.twig', [
                'dietaryPreferences' => $dietaryPreferences,
                'selectedDiets' => $selectedDiets,
                'API_recipes' => [],
                'Db_recipes'=>$DbRecipes,
                'user' => $user,
                'alert'=>$alertInfo
            ]);

        }
        else{
            // Fetch my recipes from the DB and set is my TRUE
            echo "Fetched Recipe IDs:\n";
            //print_r($recipe[0]);
            return $this->redirectToRoute('update_profile');
        }

    }

    #[Route('/recipeSubmission', name: 'recipeSubmission')]
    public function recipeSubmission(Request $request, EntityManagerInterface $em, LoggerInterface $logger, SessionInterface $session): Response
    {
        $recipe = new Recipes();

        // Retrieve user ID from session and set it to the recipe entity
        $userId = $session->get('userId');
        if (!$userId) {
            throw $this->createAccessDeniedException('You must be logged in to submit a recipe.');
        }
        $recipe->setUserId($userId);

        $form = $this->createFormBuilder($recipe)
            ->add('recipeName', TextType::class, ['label' => 'Recipe Name'])
            ->add('recipeDescription', TextareaType::class, ['label' => 'Recipe Description', 'required' => false])
            ->add('picture', FileType::class, [
                'label' => 'Recipe Image',
                'required' => false,
                'mapped' => false,
                'attr' => ['accept' => 'image/*'] // Accepts all image files
            ])
            ->add('cost', ChoiceType::class, [
                'choices' => ['€' => '1', '€€' => '2', '€€€' => '3'],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'form-label-cost']
            ])
            ->add('time', IntegerType::class, ['label' => 'Cooking Time (minutes)'])
            ->add('calories', IntegerType::class, ['label' => 'Calories', 'required' => false])
            ->add('fat', IntegerType::class, ['label' => 'Fat', 'required' => false])
            ->add('carbs', IntegerType::class, ['label' => 'Carbohydrates', 'required' => false])
            ->add('protein', IntegerType::class, ['label' => 'Protein', 'required' => false])
            ->add('servings', IntegerType::class, ['label' => 'Number of Servings'])
            ->add('diet', ChoiceType::class, [
                'label' => 'Diet',
                'choices' => [
                    'Vegetarian' => 'vegetarian',
                    'Vegan' => 'vegan',
                    'Gluten Free' => 'gluten_free',
                    'Keto' => 'keto',
                    'Paleo' => 'paleo'
                ],
                'placeholder' => 'Select a diet',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Meal Type',
                'choices' => [
                    'Breakfast' => 'breakfast',
                    'Lunch' => 'lunch',
                    'Dinner' => 'dinner',
                    'Snack' => 'snack',
                    'Dessert' => 'dessert'
                ],
                'placeholder' => 'Select a meal type',
            ])
            ->add('ingredients', HiddenType::class, ['mapped' => false])
            ->add('ingredientsAmounts', HiddenType::class, ['mapped' => false, 'required' => false])
            ->add('ingredientsUnits', HiddenType::class, ['mapped' => false, 'required' => false])
            ->add('instructions', HiddenType::class, ['mapped' => false])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                // Generate a unique name for the file before saving it
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $pictureFile->move(
                        $this->getParameter('recipe_images_directory'), // path to public/style/images/recipeImages
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                // Store the file name in the entity
                $recipe->setPicturePath($newFilename);
            }

            // Get ingredients, quantities, units, and instructions from hidden fields
            $ingredientsJSON = json_decode($form->get('ingredients')->getData(), true);
            $ingredientsAmountsJSON = json_decode($form->get('ingredientsAmounts')->getData(), true);
            $ingredientsUnitsJSON = json_decode($form->get('ingredientsUnits')->getData(), true);
            $instructionsJSON = json_decode($form->get('instructions')->getData(), true);

            // Set the ingredients and instructions data to the recipe entity as JSON arrays
            $recipe->setIngredients($ingredientsJSON ?? []);
            $recipe->setIngredientsAmounts($ingredientsAmountsJSON ?? []);
            $recipe->setIngredientsUnits($ingredientsUnitsJSON ?? []);
            $recipe->setInstructions($instructionsJSON ?? []);

            // Save the recipe to the database
            $em->persist($recipe);
            $em->flush();


            $id = $recipe->getId(); // Assuming 'id' is the auto-generated primary key
            return $this->redirectToRoute('recipeDisplay', ['id' => $id]);
        }

        return $this->render('Pages/recipeSubmission.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/SaveRecipeDisplay/{id}/{save}/{isApi}', name: 'SaveRecipeDisplay')]
    public function SaveRecipeDisplay($id,$save,$isApi,Request $request, EntityManagerInterface $em, SessionInterface $session,SpoonacularApiService $apiService): Response
    {
        if (!$session->get('isOnline'))
        {
            return $this->redirectToRoute('index');
        }

        $userId = $session->get('userId');
        $user = $em->getRepository(User::class)->findOneBy(['id' =>$userId]);


        //!!!!Validating the input !!!!!!///
        if (!is_numeric($id) || !in_array($save, ['0', '1', '3']) || !in_array($isApi, ['0', '1'])) {
            $this->addFlash('error', 'Invalid request parameters.');
            return $this->redirectToRoute('index');
        }

        if ($save=='1')
        {
            $existingRecipe = $em->getRepository(SavedRecipes::class)->findOneBy([
                'userId' => $user,
                'recipeId' => $id,
                'isApi' => $isApi
            ]);

            //!!!Validation!!!!!
            if ($existingRecipe) {
                $this->addFlash('error', 'Recipe already saved.');
                return $this->redirectToRoute('recipeDisplay', ['id' => $id]);
            }

            //$user = $em->getRepository(User::class)->findOneBy(['id' =>$userId]);
            $savedRecipe=new SavedRecipes();
            $savedRecipe->setRecipeId($id);
            $savedRecipe->setUserId($user);
            $savedRecipe->setIsApi($isApi);
            $savedRecipe->setIsMyRecipe(0);
            $em->persist($savedRecipe);
            $em->flush();

        }
        elseif ($save=='0')
        {
            //!!!Validation!!!!!

            $savedRecipe = $em->getRepository(SavedRecipes::class)->findOneBy([
                'userId' => $user, // Assuming this is an association with the User entity
                'recipeId' => $id, // The recipe ID you are looking to delete
                'isApi' => $isApi, // Assuming this is a condition you also want to match
            ]);

            if ($savedRecipe) {
                // Remove the recipe from the database
                $em->remove($savedRecipe);
                $em->flush(); // Commit the changes to the database

                // Optionally, add a success message or some form of confirmation
                $this->addFlash('success', 'Recipe has been successfully deleted.');
            } else {
                // Optionally, handle the case where no recipe was found to delete
                $this->addFlash('error', 'No matching recipe found to delete.');
            }

        }
        elseif ($save==='3')
        {

            $user2Id = $em->getRepository(Recipes::class)->findOneBy(['id'=>$id])->getUserId();

            //!!!Validation!!!!!
            if (!$user2Id) {
                $this->addFlash('error', 'Recipe not found.');
                return $this->redirectToRoute('recipeDisplay', ['id' => $id]);
            }


            $user2 = $em->getRepository(User::class)->findOneBy(['id' =>$user2Id]);

            //!!!Validation!!!!!
            if ($user2Id == $user->getId()) {
                $this->addFlash('error', 'You cannot follow yourself.');
                return $this->redirectToRoute('recipeDisplay', ['id' => $id]);
            }

            // Check if already following
            $existingFollow = $em->getRepository(Following::class)->findOneBy([
                'userId' => $user,
                'followingId' => $user2Id
            ]);
            //!!!Validation!!!!!
            if ($existingFollow) {
                $this->addFlash('error', 'Already following this user.');
                return $this->redirectToRoute('user_profile', ['username' => $user2Id->getUsername()]);
            }

            //Add if there is no User2 :)
            $follow=new Following();
            $follow->setUserId($user);
            $follow->setFollowingId($user2);
            $em->persist($follow);
            $em->flush();

            //add the follower to the follower list
            $follower=new Followers();
            $follower->setUserId($user2);
            $follower->setFollowerId($user);
            $em->persist($follower);
            $em->flush();
            return $this->redirectToRoute('user_profile', ['username' => $user2->getUsername(),
                'isFollowing'=>'1']);

        }

        return $this->redirectToRoute('recipeDisplay', ['id' => $id]);

    }


    #[Route('/recipeDisplay/{id}', name: 'recipeDisplay')]
    public function display($id,Request $request, EntityManagerInterface $em, SessionInterface $session,SpoonacularApiService $apiService): Response
    {
        if (!$session->get('isOnline'))
        {
            return $this->redirectToRoute('index');
        }

        //!!!Validation!!!!!
        if (!is_numeric($id)) {
            $this->addFlash('error', 'Invalid recipe ID provided.');
            return $this->redirectToRoute('index');
        }
        $userId = $session->get('userId');
        $recipe = $em->getRepository(Recipes::class)->find($id);
        $isFromApi=0;

        if (!$recipe) {
            try {
                $recipe = $apiService->getRecipeByIdFordisplay($id);
                if ($recipe) {
                    $isFromApi = 1;
                } else {
                    $recipe=[];
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Failed to fetch recipe: ' . $e->getMessage());
                return $this->redirectToRoute('homePage');
            }
        }
        $isSaveRecipe=0;
        $isFollowing=0;
        if ($recipe ) {
            //check if the recipe already saved to your recipes.
            $savedRecipe = $em->getRepository(SavedRecipes::class)->findBy(['userId' => $userId, 'recipeId' => $id]);
            $isSaveRecipe = empty($savedRecipe) ? true : false;

        }

        // Check if you follow the owner of the recipe
        $isFollowing = false;
        if ($recipe && !$isFromApi) {
            $recipeOwnerId = $recipe->getUserId();
            $recipeOwner = $em->find(User::class, $recipeOwnerId);
            $isFollowing = $em->getRepository(Following::class)->findOneBy(['userId' => $userId, 'followingId' => $recipeOwnerId]) ? true : false;
        }

        // Handling comment submission
        if ($request->isMethod('POST')) {
            $commentText = $request->request->get('comment');

            $comment = new Comments();

            $user = $em->getRepository(User::class)->find($userId);
            if (!$user) {
                throw new \Exception('User not found for id: ' . $userId); // Handle error appropriately
            }
            $comment->setUserId($user);

            $comment->setComment($commentText);

            if ($recipe instanceof Recipes) {
                $comment->setRecipeId($recipe); // Set the Recipe entity object
            } elseif (is_array($recipe) && isset($recipe['id'])) {
                // Set the ID directly if fetched from API
                $comment->setRecipeId($recipe['id']); // Set the ID from the fetched API data
            } else {
                // If $recipe doesn't have a valid ID
                $this->addFlash('error', 'Recipe ID is missing or invalid.');
                return $this->redirectToRoute('index');
            }

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('recipeDisplay', ['id' => $id]);
        }


        // Fetch comments for that recipe
        $comments = [];
        if ($recipe instanceof Recipes) {
            $comments = $em->getRepository(Comments::class)->findBy(['recipeId' => $recipe->getId()]);
        } elseif (is_array($recipe) && isset($recipe['id'])) {
            $comments = $em->getRepository(Comments::class)->findBy(['recipeId' => $recipe['id']]);
        }

        // Fetch user entity by userId
        $user = $em->getRepository(User::class)->find($userId);

        if ($isFromApi) {
            return $this->render('Pages/recipeDisplay.html.twig', [
                'recipe' => $recipe,
                'APIFlag' => $isFromApi,
                'SaveFlag' => $isSaveRecipe,
                'followFlag' => $isFollowing,
                'userId' => $userId,
                'comments' => $comments,
                'user' => $user
            ]);
        }
        else {
            return $this->render('Pages/recipeDisplay.html.twig', [
                'recipe' => $recipe,
                'APIFlag' => $isFromApi,
                'SaveFlag' => $isSaveRecipe,
                'followFlag' => $isFollowing,
                'userId' => $userId,
                'comments' => $comments,
                'user' => $user,
                'recipeOwner' => $recipeOwner
            ]);
        }

    }


    #[Route('/profile/{username}/{isFollowing}', name: 'user_profile')]
    public function showUserProfile(
        EntityManagerInterface $entityManager,
        string $username,
        string $isFollowing,
        Request $request,
        SessionInterface $session,
        LoggerInterface $logger,
        SpoonacularApiService $apiService
    ): Response {
        // Find the user by username
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        // If user not found, throw an exception or handle the error as needed
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $type = $request->query->get('type');
        $profileUserId = $user->getId();

        $API_recipes = [];
        $Db_recipes = [];

        if ($type === 'delete') {
            $sessionUserId = $session->get('userId');
            $following = $entityManager->getRepository(Following::class)->findOneBy([
                'userId' => $sessionUserId,
                'followingId' => $profileUserId
            ]);

            if ($following) {
                try {
                    $entityManager->getRepository(Following::class)->removeFollowingByUserAndFollowing(
                        $following->getUserId()->getId(),
                        $following->getFollowingId()->getId()
                    );
                    $entityManager->flush();

                    $follower = $entityManager->getRepository(Followers::class)->findOneBy([
                        'userId' => $following->getFollowingId()->getId(),
                        'followerId' => $following->getUserId()->getId()
                    ]);

                    if ($follower)
                    {
                        $entityManager->remove($follower);
                        $entityManager->flush();

                    }

                } catch (\Exception $e) {
                    $logger->error('Error during following deletion flush: ' . $e->getMessage());
                }

                // Redirect to the follows page
                return $this->redirectToRoute('follows');
            }

        } elseif ($type === 'RemoveFollower') {
            $sessionUserId = $session->get('userId');
            $followers = $entityManager->getRepository(Followers::class)->findOneBy([
                'userId' => $sessionUserId,
                'followerId' => $profileUserId
            ]);

            if ($followers) {
                try {
                    $entityManager->getRepository(Followers::class)->removeFollowingByUserAndFollowers(
                        $followers->getUserId()->getId(),
                        $followers->getFollowerId()->getId()
                    );
                    $entityManager->flush();

                    $following = $entityManager->getRepository(Following::class)->findOneBy([
                        'userId' => $followers->getFollowerId()->getId(),
                        'followingId' => $followers->getUserId()->getId()
                    ]);

                    if ($following)
                    {
                        $entityManager->remove($following);
                        $entityManager->flush();
                    }


                } catch (\Exception $e) {
                    $logger->error('Error during follower deletion flush: ' . $e->getMessage());
                }

                // Redirect to the follows page
                return $this->redirectToRoute('follows');
            }
        } elseif ($type === 'saved') {
            // Fetch saved recipes from the API & DB
            $recipeIds = $entityManager->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($profileUserId, 1, 0);
            if (!empty($recipeIds)) {
                try {
                    $API_recipes = $apiService->getRecipesInformationBulk($recipeIds);
                } catch (\Exception $e) {
                    $logger->error('Error fetching API recipes: ' . $e->getMessage());
                }
            }

            $recipeIds = $entityManager->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($profileUserId, 0, 0);
            $Db_recipes = $entityManager->getRepository(Recipes::class)->findRecipesByIdsAndDiets($recipeIds);
        } elseif ($type === 'user recipe') {
            // Fetch user's own recipes
            $recipeIds = $entityManager->getRepository(Recipes::class)->findIdsByUserId($profileUserId);

            // $recipeIds = $entityManager->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($profileUserId, 0, 1);
            $Db_recipes = $entityManager->getRepository(Recipes::class)->findRecipesByIdsAndDiets($recipeIds);
        }
        else

        {
            $sessionUserId = $session->get('userId');
            $following = $entityManager->getRepository(Following::class)->findOneBy([
                'userId' => $sessionUserId,
                'followingId' => $profileUserId]);
            $followers = $entityManager->getRepository(Followers::class)->findOneBy([
                'userId' => $sessionUserId,
                'followerId' => $profileUserId
            ]);
            if ((!$followers) and (!$following))
            {
                $isFollowing='3';
            }
        }

        // Render the user profile template
        return $this->render('Pages/User.html.twig', [
            'user' => $user,
            'isFollowing' => $isFollowing,
            'API_recipes' => $API_recipes,
            'Db_recipes' => $Db_recipes,
        ]);
    }

    #[Route('/recipe_delete/{id}', name: 'recipe_delete')]
    public function recipe_delete(Request $request, EntityManagerInterface $em, $id): Response
    {
        $recipe = $em->getRepository(Recipes::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('No recipe found for id '.$id);
        }

        $em->remove($recipe);
        $em->flush();

        return $this->redirectToRoute('homePage');
    }

    #[Route('/recipe_edit/{id}', name: 'recipe_edit')]
    public function edit($id, Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $recipe = $em->getRepository(Recipes::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('No recipe found for id ' . $id);
        }

        // Create the form with the existing recipe data
        $form = $this->createFormBuilder($recipe)
            ->add('recipeName', TextType::class, ['label' => 'Recipe Name', 'data' => $recipe->getRecipeName()])
            ->add('recipeDescription', TextareaType::class, ['label' => 'Recipe Description', 'required' => false, 'data' => $recipe->getRecipeDescription()])
            ->add('picture', FileType::class, ['label' => 'Recipe Image', 'required' => false, 'mapped' => false, 'attr' => ['accept' => 'image/*'],'data_class' => null])
            ->add('cost', ChoiceType::class, ['choices' => ['€' => '1', '€€' => '2', '€€€' => '3'], 'expanded' => true, 'multiple' => false, 'attr' => ['class' => 'form-label-cost'], 'data' => $recipe->getCost(),])
            ->add('time', IntegerType::class, ['label' => 'Cooking Time (minutes)', 'data' => $recipe->getTime()])
            ->add('calories', IntegerType::class, ['label' => 'Calories', 'required' => false, 'data' => $recipe->getCalories()])
            ->add('fat', IntegerType::class, ['label' => 'Fat', 'required' => false, 'data' => $recipe->getFat()])
            ->add('carbs', IntegerType::class, ['label' => 'Carbohydrates', 'required' => false, 'data' => $recipe->getCarbs()])
            ->add('protein', IntegerType::class, ['label' => 'Protein', 'required' => false, 'data' => $recipe->getProtein()])
            ->add('servings', IntegerType::class, ['label' => 'Number of Servings', 'data' => $recipe->getServings()])
            ->add('diet', ChoiceType::class, ['label' => 'Diet', 'choices' => [
                    'No diet' => 'no diet',
                    'Vegetarian' => 'vegetarian',
                    'Vegan' => 'vegan',
                    'Gluten Free' => 'gluten_free',
                    'Keto' => 'keto',
                    'Paleo' => 'paleo'
                ], 'data' => $recipe->getDiet(), 'placeholder' => 'Select a diet'])
            ->add('type', ChoiceType::class, [
                'label' => 'Meal Type',
                'choices' => [
                    'Breakfast' => 'breakfast',
                    'Lunch' => 'lunch',
                    'Dinner' => 'dinner',
                    'Snack' => 'snack',
                    'Dessert' => 'dessert'
                ], 'data' => $recipe->getType(), 'placeholder' => 'Select a meal type'])
            ->add('ingredients', HiddenType::class, ['mapped' => false, 'data' => json_encode($recipe->getIngredients())])
            ->add('ingredientsAmounts', HiddenType::class, ['mapped' => false, 'required' => false, 'data' => json_encode($recipe->getIngredientsAmounts())])
            ->add('ingredientsUnits', HiddenType::class, ['mapped' => false, 'required' => false, 'data' => json_encode($recipe->getIngredientsUnits())])
            ->add('instructions', HiddenType::class, ['mapped' => false, 'data' => json_encode($recipe->getInstructions())])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                // Generate a unique name for the file before saving it
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictureFile->move(
                        $this->getParameter('recipe_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                    // $logger->error('Error uploading file: ' . $e->getMessage());
                }

                // Store the file name in the entity
                $recipe->setPicturePath($newFilename);
            }

            // Get ingredients, quantities, units, and instructions from hidden fields
            $ingredientsJSON = json_decode($form->get('ingredients')->getData(), true);
            $ingredientsAmountsJSON = json_decode($form->get('ingredientsAmounts')->getData(), true);
            $ingredientsUnitsJSON = json_decode($form->get('ingredientsUnits')->getData(), true);
            $instructionsJSON = json_decode($form->get('instructions')->getData(), true);

            // Set the data to the recipe entity
            $recipe->setIngredients($ingredientsJSON ?? []);
            $recipe->setIngredientsAmounts($ingredientsAmountsJSON ?? []);
            $recipe->setIngredientsUnits($ingredientsUnitsJSON ?? []);
            $recipe->setInstructions($instructionsJSON ?? []);

            // Save the edited recipe to the database
            $em->flush();

            return $this->redirectToRoute('recipeDisplay', ['id' => $recipe->getId()]);
        }

        // Render the edit form
        return $this->render('Pages/editRecipe.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    #[Route('/updateProfile', name: 'update_profile')]
    public function updateProfile(Request $request, EntityManagerInterface $em, LoggerInterface $logger, SessionInterface $session, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Retrieve user ID from session
        $userId = $session->get('userId');
        if (!$userId) {
            throw $this->createAccessDeniedException('You must be logged in to update your profile.');
        }

        // Find the user in the database
        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, ['label' => 'Username'])
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('surname', TextType::class, ['label' => 'Surname'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('current_password', PasswordType::class, [
                'label' => 'Current Password',
                'mapped' => false,
                'required' => true, // Password is required to update profile info
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        $alertMessage = null;

        if ($form->isSubmitted() && $form->isValid()) {
            // Verify the current password
            $currentPassword = $form->get('current_password')->getData();
            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                // Set alert message if the current password is incorrect
                $alertMessage = 'Current password is incorrect.';
            } else {
                // Proceed with updating the user
                $em->persist($user);
                $em->flush();
                $session->set('username', $user->getUsername());

                $alertMessage = 'Profile updated successfully!';
                return $this->redirectToRoute('profile');
            }
        }

        return $this->render('Pages/updateUserProfile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'alertMessage' => $alertMessage
        ]);
    }





}

