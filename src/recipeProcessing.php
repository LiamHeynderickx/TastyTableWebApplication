<?php
require_once 'DB.php';

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $recipeName = $_POST['recipe_name'];
    $recipeDescription = $_POST['recipe_description'];
    //$recipeImage = ($_POST['recipe_image']); // NOT WORKING
    $recipeType = $_POST['recipe_type'];
    $servings = $_POST['servings'];
    $time = $_POST['cooking_time'];
    $calories = $_POST['calories'];
    $fats = $_POST['fats'];
    $carbs = $_POST['carbs'];
    $proteins = $_POST['proteins'];
    $instructions = $_POST['instruction'];
    $ingredients = $_POST['ingredient'];
    $quantities = $_POST['quantity'];
    $units = $_POST['unit'];
    // Create JSON arrays for the arrays
    $instructionsJson = json_encode($instructions);
    $ingredientDataJson = json_encode($ingredients);
    $quantityDataJson = json_encode($quantities);
    $unitDataJson = json_encode($units);

    // Handle image upload
    $recipeImage = null; // Initialize variable to store image data
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
        // Get image data
        $recipeImage = file_get_contents($_FILES['recipe_image']['tmp_name']);
    }

    // Check if image upload was successful
    if ($recipeImage === null) {
        echo "Error: Image upload failed.";
        exit();
    }

    // Insert recipe data into the database
    $query = "INSERT INTO recipes (recipeName, recipeDescription, recipeImage, type, servings, time, calories, fats, carbs, proteins, instructions, ingredients, ingredientsQuantity, amountUnits) 
              VALUES (:recipeName, :recipeDescription, :recipeImage, :recipeType, :servings, :time, :calories, :fats, :carbs, :proteins, :instructions, :ingredients, :quantities, :units)";
    $params = array(
        ':recipeName' => $recipeName,
        ':recipeDescription' => $recipeDescription,
        ':recipeImage' => $recipeImage,
        ':recipeType' => $recipeType,
        ':servings' => $servings,
        ':time' => $time,
        ':calories' => $calories,
        ':fats' => $fats,
        ':carbs' => $carbs,
        ':proteins' => $proteins,
        ':instructions' => $instructionsJson,
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
