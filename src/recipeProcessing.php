<?php
require_once 'DB.php';

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $recipeName = $_POST['recipe_name'];
    $recipeDescription = $_POST['recipe_description'];
    $servings = $_POST['servings'];
    $time = $_POST['cooking_time'];
    $calories = $_POST['calories'];
    $instructions = $_POST['instructions'];
    // Retrieve ingredients, quantities, and units
    $ingredients = $_POST['ingredient'];
    $quantities = $_POST['quantity'];
    $units = $_POST['unit'];

    // Create JSON arrays for ingredients, quantities, and units
    $ingredientDataJson = json_encode($ingredients);
    $quantityDataJson = json_encode($quantities);
    $unitDataJson = json_encode($units);

    // Insert recipe data into the database
    $query = "INSERT INTO recipes (recipeName, recipeDescription, servings, time, calories, instructions, ingredients, ingredientsQuantity, amountUnits) 
              VALUES (:recipeName, :recipeDescription, :servings, :time, :calories, :instructions, :ingredients, :quantities, :units)";
    $params = array(
        ':recipeName' => $recipeName,
        ':recipeDescription' => $recipeDescription,
        ':servings' => $servings,
        ':time' => $time,
        ':calories' => $calories,
        ':instructions' => $instructions,
        ':ingredients' => $ingredientDataJson,
        ':quantities' => $quantityDataJson,
        ':units' => $unitDataJson
    );
    executeQuery($query, $params);

    // Redirect to recipe display page after successful insertion
    header("Location: recipeDisplay.php");
    exit();
}
?>
