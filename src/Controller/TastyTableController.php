<?php

namespace App\Controller;

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

        $session->clear();
        return $this->redirectToRoute('index');

    }

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

    #[Route('/homePageAPI', name: 'homePageAPI')]
    public function homePageAPI(Request $request, EntityManagerInterface $em, SessionInterface $session,SpoonacularApiService $apiService): Response
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

    #[Route('/search', name: 'search_recipes')]
    public function searchRecipes(Request $request, SpoonacularApiService $apiService): Response
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
                }
            }

            $recipeIds = $em->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($UserID, 0,0);
            //$DbRecipes = $em->getRepository(Recipes::class)->findBy(['id' => $recipeIds]);
            $DbRecipes = $em->getRepository(Recipes::class)->findRecipesByIdsAndDiets($recipeIds, $diets);



            return $this->render('Pages/Profile.html.twig', [
                'dietaryPreferences' => $dietaryPreferences,
                'selectedDiets' => $selectedDiets,
                'API_recipes' => $ApiRecipes,
                'Db_recipes'=>$DbRecipes,
                'user' => $user
            ]);

        }elseif ($type === 'my'){
            // Fetch my recipes from the DB and set is my TRUE
            $recipeIds = $em->getRepository(SavedRecipes::class)->findRecipeIdsByUserAndIsApi($UserID, 0,1);
            //$DbRecipes = $em->getRepository(Recipes::class)->findBy(['id' => $recipeIds]);
            $DbRecipes = $em->getRepository(Recipes::class)->findRecipesByIdsAndDiets($recipeIds, $diets);



            return $this->render('Pages/Profile.html.twig', [
                'dietaryPreferences' => $dietaryPreferences,
                'selectedDiets' => $selectedDiets,
                'API_recipes' => [],
                'Db_recipes'=>$DbRecipes,
                'user' => $user
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

        //$recipe->setUserId(1);

        $form = $this->createFormBuilder($recipe)
            ->add('recipeName', TextType::class, ['label' => 'Recipe Name'])
            ->add('recipeDescription', TextareaType::class, ['label' => 'Recipe Description', 'required' => false])
            ->add('picture', FileType::class, [
                'label' => 'Recipe Image',
                'required' => false,
                'mapped' => false, // Not mapped directly to the entity
                'attr' => ['accept' => 'image/*'] // Limit to image files
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
                $recipe->setPicturePath($newFilename); // Corrected method name
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
        if ($save=='1')
        {

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


        return $this->redirectToRoute('recipeDisplay', ['id' => $id]);

    }


    #[Route('/recipeDisplay/{id}', name: 'recipeDisplay')]
    public function display($id,Request $request, EntityManagerInterface $em, SessionInterface $session,SpoonacularApiService $apiService): Response
    {
        if (!$session->get('isOnline'))
        {
            return $this->redirectToRoute('index');
        }
        $recipe = $em->getRepository(Recipes::class)->find($id);
        $isFromApi=0;

        if (!$recipe) {
            //throw $this->createNotFoundException('The recipe does not exist');
            // $recipe=$apiService->getRecipeById($id);
            //$ids[]=$id;

            $recipe = ($apiService->getRecipeByIdFordisplay($id));
            if ($recipe){
                $isFromApi=1;
            }
            else
            {
                $recipe=[] ;
            }

        }
        $isSaveRecipe=0;
        $isFollowing=0;
        if ($recipe)
        {
            //check if the recipe already saved to your recipes.
            $userId = $session->get('userId');
            $savedRecipe=$em->getRepository(SavedRecipes::class)->findBy(['userId'=>$userId,'recipeId'=>$id]);
            $isSaveRecipe = empty($savedRecipe) ? true : false;

            //Check if you follow the owner of the recipe


            $userId = $session->get('userId');
            $user = $em->getRepository(User::class)->findOneBy(['id' =>$userId]);
//
//
            $currentRecipe=$em->getRepository(Recipes::class)->findOneBy(['id'=>$id]);
            $recipeOwner=$currentRecipe->getUserId();
//
            $result=$em->getRepository(Following::class)->findBy(['userId'=>$user,'followingId'=>$recipeOwner]);
            //if the returned array emty then user is not following the owner
            $isFollowing = empty($result) ? true : false;

        }
        // echo "Fetched Recipe IDs:\n";
        //print_r($recipe[0]);



        return $this->render('Pages/recipeDisplay.html.twig', [
            'recipe' => $recipe,
            'APIFlag' => $isFromApi,
            'SaveFlag'=>$isSaveRecipe,
            'followFlag'=>$isFollowing
        ]);
    }


    #[Route('/profile/{username}/{isFollowing}', name: 'user_profile')]
    public function showUserProfile(EntityManagerInterface $entityManager, string $username,string $isFollowing,Request $request,SessionInterface $session): Response
    {
        // Find the user by username
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        $type = $request->query->get('type');


        // If user not found, throw an exception or handle the error as needed
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($user->getDietPreference() === null) {
            $user -> setDietPreference('');
        }

        if ($type === 'delete')
        {
            $userId = $session->get('userId');
            //$mainUser = $entityManager->getRepository(User::class)->findOneBy(['username' => $userId]);
            $following = $entityManager->getRepository(Following::class)->findOneBy(['userId' =>$userId, 'followingId' => $user->getId()]);

            if ($following) {
                echo "Following entity ID: " . $following->getId() . "<br>";
                echo "Following entity User ID: " . $following->getUserId()->getId() . "<br>";
                echo "Following entity Following ID: " . $following->getFollowingId()->getId() . "<br>";



                // $entityManager->remove($following);
                $entityManager->getRepository(Following::class)->removeFollowingByUserAndFollowing($following->getUserId()->getId() ,$following->getFollowingId()->getId());
                // Debugging: Before flushing
                echo "Before flush<br>";
                try {
                    $entityManager->flush();
                    // echo "Flush successful<br>";
                } catch (\Exception $e) {
                    echo "Error during flush: " . $e->getMessage() . "<br>";
                }
                // Debugging: After flushing
                //echo "After flush<br>";

                // Redirect to the follows page
                return $this->redirectToRoute('follows');
            }


        }
        elseif ($type=='RemoveFollower')
        {
            $userId = $session->get('userId');
            $followers = $entityManager->getRepository(Followers::class)->findOneBy(['userId' =>$userId, 'followerId' => $user->getId()]);

            if ($followers) {
                echo "Following entity ID: " . $followers->getId() . "<br>";
                echo "Following entity User ID: " . $followers->getUserId()->getId() . "<br>";
                echo "Following entity Following ID: " . $followers->getFollowerId()->getId() . "<br>";



                // $entityManager->remove($following);
                $entityManager->getRepository(Followers::class)->removeFollowingByUserAndFollowers($followers->getUserId()->getId() ,$followers->getFollowerId()->getId());
                // Debugging: Before flushing
                echo "Before flush<br>";
                try {
                    $entityManager->flush();
                    // echo "Flush successful<br>";
                } catch (\Exception $e) {
                    echo "Error during flush: " . $e->getMessage() . "<br>";
                }
                // Debugging: After flushing
                //echo "After flush<br>";

                // Redirect to the follows page
                return $this->redirectToRoute('follows');
            }


        }
        // Render the user profile template
        return $this->render('Pages/User.html.twig', [
            'user' => $user,
            'isFollowing'=>$isFollowing,
        ]);
    }

    #[Route('/recipe/delete/{id}', name: 'recipe_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $recipe = $em->getRepository(Recipes::class)->find($id);

        if ($recipe) {
            $em->remove($recipe);
            $em->flush();

            $this->addFlash('success', 'Recipe deleted successfully.');
        } else {
            $this->addFlash('error', 'Recipe not found.');
        }

        return $this->redirectToRoute('homePage'); // Change 'home' to your desired route
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

        $originalPassword = $user->getPassword(); // Store the original password

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, ['label' => 'Username'])
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('surname', TextType::class, ['label' => 'Surname'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Password', 'required' => false]) // Make password optional
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If the password is not empty, encode it
            if ($form->get('password')->getData()) {
                $password = $passwordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($password);
            } else {
                $user->setPassword($originalPassword); // Keep the original password if no new password is provided
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('profile');
        }

        return $this->render('Pages/updateUserProfile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    #[Route('/addSavedRecipe', name: 'addSaved')]
    public function recipeSaveOrDelete(Request $request, EntityManagerInterface $em, SessionInterface $session,SpoonacularApiService $apiService): Response
    {
//        $user = $em->getRepository(User::class)->findOneBy(['id' =>1]);
//        $user2=$em->getRepository(User::class)->findOneBy(['id' =>22]);
//
//        $follow=new Followers();
//        $follow->setUserId($user);
//        $follow->setFollowerId($user2);
//        $em->persist($follow);
//        $em->flush();

        return $this->redirectToRoute('index');

    }

}

