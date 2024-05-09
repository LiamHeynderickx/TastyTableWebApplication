<?php

global $db;
require_once 'DB.php'; // include the database connection

// Fetch recipe data from the database
$query = $db->prepare('SELECT * FROM recipes WHERE recipeId = 6');
$query->execute();
$recipeData = $query->fetch(PDO::FETCH_ASSOC);

// Check if data is retrieved
if ($recipeData) {
    // Access individual fields
    $recipeName = $recipeData['recipeName'];
    $recipeDescription = $recipeData['recipeDescription'];
    $recipeImage = $recipeData['recipeImage'];
    $recipeType = $recipeData['type'];
    $servings = $recipeData['servings'];
    $time = $recipeData['time'];
    $calories = $recipeData['calories'];
    $fats = $recipeData['fats'];
    $carbs = $recipeData['carbs'];
    $proteins = $recipeData['proteins'];
    $instructions = json_decode($recipeData['instructions'], true);
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
            <?php
            // Check if recipe image data is available
            if ($recipeImage !== null) {
                // Convert binary image data to base64 format
                $base64Image = base64_encode($recipeImage);
                // Create an image tag with base64-encoded image data
                echo "<img src='data:image/jpeg;base64,$base64Image' alt='Recipe Image' style='width: 220px; height: 220px;'>";
            } else {
                // If no image data is available, display a placeholder image
                echo "<img src='../public/placeholder.jpg' alt='Recipe Image' style='width: 220px; height: 220px;'>";
            }
            ?>

            <!-- Ingredients -->
        <div class="ingredients">
            <h3>Servings</h3>
            <!-- Servings calculator -->
            <div>
                <button onclick="decreaseServings()">-</button>
                <span id="servings"><?php echo $servings ?></span>
                <button onclick="increaseServings()">+</button>
            </div>
            <!-- List ingredient info from database -->
            <?php
            // Check if $ingredients is not empty
            if(!empty($ingredients)) {
                echo "<h2>Ingredients:</h2>";
                echo "<ul>";

                // Loop through each index
                for($i = 0; $i < count($ingredients); $i++) {
                    $ingredient = $ingredients[$i];
                    $quantity = $quantities[$i];
                    $unit = $units[$i];

                    echo "<li><span class='ingredient-amount' data-original-amount='$quantity'>$quantity</span>$unit $ingredient</li>";
                }

                echo "</ul>";
            } else {
            echo "No ingredients found.";
            }
            ?>
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
            <h2><?php echo $recipeName ?></h2>

            <p><?php echo $recipeDescription ?></p>

            <p>Cooking Time: <?php echo $time ?> minutes | Calories: <?php echo $calories ?> kcal</p>

            <p>Fats: <?php echo $fats ?>g | Carbs: <?php echo $carbs ?>g | Proteins: <?php echo $proteins ?></p>

            <p>Price range: € | Rating: ★★★★★</p>

            <!-- Instructions -->
            <div>
                <h3>Instructions</h3>
                <?php
                // Check if $instructions is not empty
                if (!empty($instructions)) {
                    // Initialize an empty string to store the instructions
                    $instructionList = '';

                    // Loop through each instruction
                    for ($i = 0; $i < count($instructions); $i++) {
                        $stepNumber = $i + 1;
                        // Concatenate the step number, line break, and instruction to the instruction list
                        $instructionList .= "<strong>Step $stepNumber:</strong><br>" . $instructions[$i] . "<br><br>";
                    }

                    // Output the instruction list without bullet points
                    echo "<div>$instructionList</div>";
                } else {
                    echo "No instructions found.";
                }
                ?>

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
                ingredient.textContent = includesG ? parseFloat(newAmount.toFixed(0)) + 'g' : parseFloat(newAmount.toFixed(0));
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
