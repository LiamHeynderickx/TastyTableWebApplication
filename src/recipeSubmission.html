<!DOCTYPE html>
<html lang="en">
<head>
    <!-- JqueryImport for the header and footer -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasty Table</title>
    <link rel="stylesheet" href="recipeSubmission.css">
    <script src="https://kit.fontawesome.com/4c4c8749b1.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="header"></div>

<form action="recipeProcessing.php" method="POST" enctype="multipart/form-data">
    <h1>Submit Your Recipe</h1>

    <label for="recipe_name">Recipe Name:</label><br>
    <input type="text" id="recipe_name" name="recipe_name" required><br><br>

    <label for="recipe_type">Meal Type:</label><br>
    <select id="recipe_type" name="recipe_type">
        <option value="breakfast">Breakfast</option>
        <option value="lunch">Lunch</option>
        <option value="dinner">Dinner</option>
        <option value="appetizer">Appetizer</option>
        <option value="dessert">Dessert</option>
        <option value="snack">Snack</option>
    </select><br><br>

    <label for="recipe_description">Recipe Description:</label><br>
    <input type="text" id="recipe_description" name="recipe_description" ><br><br>

    <label for="recipe_image">Recipe Image:</label><br>
    <input type="file" id="recipe_image" name="recipe_image" accept="image/*" ><br><br>

    <label for="servings">Number of Servings:</label><br>
    <input type="number" id="servings" name="servings" required pattern="\d+(\.\d+)?"><br><br>

    <label for="cooking_time">Cooking Time (minutes):</label><br>
    <input type="number" id="cooking_time" name="cooking_time" required pattern="\d+(\.\d+)?"><br><br>

    <label for="calories">Calories:</label><br>
    <input type="number" id="calories" name="calories" pattern="\d+(\.\d+)?"><br><br>

    <label for="fats">Fats:</label><br>
    <input type="number" id="fats" name="fats" pattern="\d+(\.\d+)?"><br><br>

    <label for="carbs">Carbs:</label><br>
    <input type="number" id="carbs" name="carbs" pattern="\d+(\.\d+)?"><br><br>

    <label for="proteins">Proteins:</label><br>
    <input type="number" id="proteins" name="proteins" pattern="\d+(\.\d+)?"><br><br>

    <label>Price Range:</label><br>
    <input type="radio" id="price_1" name="price_range" value="€" checked>
    <label for="price_1">€</label>

    <input type="radio" id="price_2" name="price_range" value="€€">
    <label for="price_2">€€</label>

    <input type="radio" id="price_3" name="price_range" value="€€€">
    <label for="price_3">€€€</label><br><br>


    <label for="ingredients">Ingredients:</label><br>
    <div id="ingredients_list">
        <div class="ingredient">
            <input type="text" name="ingredient[]" placeholder="Ingredient" required>
            <input type="text" name="quantity[]" placeholder="Quantity" pattern="\d+(\.\d+)?">
            <input type="text" name="unit[]" placeholder="Unit">
        </div>
    </div>
    <button type="button" onclick="addIngredient()">Add Ingredient</button><br><br>

    <label for="instructions">Instructions:</label><br>
    <div id="instructions_list">
        <div class="instruction">
            <span>Step 1:</span><br>
            <textarea name="instruction[]" rows="2" cols="50" required></textarea>
        </div>
    </div>
    <button type="button" onclick="addInstruction()">Add Step</button><br><br>


    <button type="submit">Submit Recipe</button><br><br>

</form>

<div id="footer"></div>


<script>

    $(function(){
        $("#header").load("Header.html");
        $("#footer").load("Footer.html");
    });

    function addIngredient() {
        var ingredientsList = document.getElementById('ingredients_list');
        var newIngredient = document.createElement('div');
        newIngredient.classList.add('ingredient');
        newIngredient.innerHTML = `
                <input type="text" name="ingredient[]" placeholder="Ingredient" required>
                <input type="text" name="quantity[]" placeholder="Quantity" >
                <input type="text" name="unit[]" placeholder="Unit" >
            `;
        ingredientsList.appendChild(newIngredient);
    }

    var step = 2;
    function addInstruction() {
        var instructionsList = document.getElementById('instructions_list');
        var lastInstruction = instructionsList.lastElementChild;
        var newStep = step++;

        var newInstruction = document.createElement('div');
        newInstruction.classList.add('instruction');
        newInstruction.innerHTML = `
        <span>Step ${newStep}:</span><br>
        <textarea name="instruction[]" rows="2" cols="50" ></textarea>
    `;
        instructionsList.appendChild(newInstruction);
    }



</script>
</body>
</html>
