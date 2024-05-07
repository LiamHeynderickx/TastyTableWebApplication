<?php

global $db;
require_once 'DB.php'; // include the database connection

// Fetch recipe data from the database
$query = $db->prepare('SELECT * FROM recipes WHERE recipeId = 1');
$query->execute();
$recipeData = $query->fetch(PDO::FETCH_ASSOC);

// Check if data is retrieved
if ($recipeData) {
    // Access individual fields
    $recipeName = $recipeData['recipeName'];
    $recipeDescription = $recipeData['recipeDescription'];
    $servings = $recipeData['servings'];
    $time = $recipeData['time'];
    $calories = $recipeData['calories'];
    $instructions = $recipeData['instructions'];
    $ingredients = json_decode($recipeData['ingredients'], true);
    $quantities = json_decode($recipeData['ingredientsQuantity'], true);
    $units = json_decode($recipeData['amountUnits'], true);
} else {
    // Handle the case where no recipe data is found
    echo "Recipe not found!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- JqueryImport for the header and footer -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasty Table</title>
    <link rel="stylesheet" href="recipeDisplay.css">
    <script src="https://kit.fontawesome.com/4c4c8749b1.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="header"></div>

<main>
    <div class="container">
        <!-- Recipe Image -->
        <aside>
            <img src="../public/burrito.jpg" alt="Recipe Image" style="width: 220px; height: 220px;">

        <!-- Ingredients -->
        <div class="ingredients">
            <h3>Ingredients</h3>
            <div>
                <button onclick="decreaseServings()">-</button>
                Servings: <span id="servings">3</span>
                <button onclick="increaseServings()">+</button>
            </div>
            <ul>
                <li><span class="ingredient-amount" data-original-amount="125g">125g</span> Rice</li>
                <li><span class="ingredient-amount" data-original-amount="350g">350g</span> Minced meat </li>
                <li><span class="ingredient-amount" data-original-amount="75g">75g</span> Mushrooms</li>
                <li><span class="ingredient-amount" data-original-amount="2">2</span> Onions</li>
                <li><span class="ingredient-amount" data-original-amount="2">2</span> Tomatoes</li>
                <li><span class="ingredient-amount" data-original-amount="3">3</span> Lettuce leaves</li>
                <li><span class="ingredient-amount" data-original-amount=""></span> Seasoning</li>
                <li><span class="ingredient-amount" data-original-amount="3">3</span> Tortilla Wraps</li>
                <li><span class="ingredient-amount" data-original-amount="50kg">50kg</span> Mozarella</li>
                <li><span class="ingredient-amount" data-original-amount=""></span> Mayonnaise</li>
                <!-- add more ingredients dynamically -->
            </ul>
        </div>
        <!-- Comments Section -->
        <div>
            <h3>Comments</h3>
            <p>Okay that shit was bussin fr fr<br>
                -Chewbacca's uncle<br>
            </p>
            <!-- Display comments dynamically here -->
            <!-- Add your own comment -->
            <textarea id="comment" placeholder="Add your comment"></textarea><br>
            <button onclick="addComment()">Add Comment</button>
        </div>
        </aside>

        <!-- Recipe Info -->
        <div>
            <h2>Ultimate Burritos <?php echo $calories; ?></h2>

            <p>Description: A burrito with a rice base filled with your own choice of meat, vegetables, cheese, sauce and seasoning but always made with love.</p>

            <p>Number of Servings: 3 | Cooking Time (minutes): 45 | Calories: idk bro</p>

            <p>Price range: € | Rating: ★★★★★</p>

            <!-- Instructions -->
            <div>
                <h3>Instructions</h3>
                <p>
                    <b>Step 1</b><br>
                    Start cooking the meat in a pan with your favourite seasoning.<br><br>
                    <b>Step 2</b><br>
                    Start cooking rice in a pot next to the pan.<br><br>
                    <b>Step 3</b><br>
                    Add your diced onions, garlic and mushrooms inside the pan and remember to mix every so often.<br><br>
                    <b>Step 4</b><br>
                    When finished with both strain the rice and mix both into the pot.<br><br>
                    <b>Step 5</b><br>
                    Cut up some tomatoes and lettuce and add them to the mix.<br><br>
                    <b>Step 6</b><br>
                    Toast the tortilla to make it warm and crunchy.<br><br>
                    <b>Step 7</b><br>
                    Spread mozzarella on the hot tortilla and then add the mix into it and some sauces, ready to devour.
                </p>
            </div>

            <!-- Buttons to like recipe and mark as done -->
            <div>
                <button onclick="likeRecipe()">Like</button>
                <button onclick="markAsDone()">Mark as Done</button>
            </div>
        </div>
    </div>
</main>

<div id="footer"></div>

<script>

    $(function(){
        $("#header").load("Header.html");
        $("#footer").load("Footer.html");
    });

    let servings = parseInt(document.getElementById('servings').textContent);
    let newServings = servings;
    function increaseServings() {
        newServings++;
        document.getElementById('servings').textContent = newServings;
        adjustIngredientAmounts(newServings);
    }

    function decreaseServings() {
        if (newServings > 1) {
            newServings--;
            document.getElementById('servings').textContent = newServings;
            adjustIngredientAmounts(newServings);
        }
    }

    function adjustIngredientAmounts(newServings) {
        var ingredientElements = document.querySelectorAll('.ingredient-amount');
        ingredientElements.forEach(function(ingredient) {
            var originalAmount = ingredient.dataset.originalAmount;
            if (originalAmount !== "") {
                var includesG = originalAmount.includes('g');
                originalAmount = parseInt(originalAmount);
                var newAmount = originalAmount * newServings / servings;
                ingredient.textContent = includesG ? parseFloat(newAmount.toFixed(2)) + 'g' : parseFloat(newAmount.toFixed(2));
            }
        });
    }


    function likeRecipe() {
        // JavaScript function to handle like button action with database
        alert('Recipe liked!');
    }

    function markAsDone() {
        // JavaScript function to handle mark as done button action with database
        alert('Recipe marked as done!');
    }
</script>
</body>
</html>
