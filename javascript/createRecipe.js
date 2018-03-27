var ingredientsDiv;
var stepsDiv;
var tagsDiv;

var titleInput;
var firstIngredient;
var firstStep;

var ingredientNum = 1;
var stepNum = 1;

var tag = "<input type='text' class='tag' name='tags[]'>";

document.addEventListener("DOMContentLoaded", function(event) {
    ingredientsDiv = document.getElementById("cookbook-create-ingredients");
    stepsDiv = document.getElementById("cookbook-create-steps");
    tagsDiv = document.getElementById("cookbook-create-tags");

    titleInput = document.getElementById("cookbook-create-title");
    firstIngredient = document.getElementById("ingr-1");
    firstStep = document.getElementById("step-1");
});

function addIngredientField() {
    ingredientNum++;
    var quantity = "<input type='text' class='ingredient_qty' name='quantity[" + ingredientNum + "][]' placeholder='Quantity'>";
    var ingredient = "<input type='text' class='ingredient_desc' name='ingredient[" + ingredientNum + "][]' placeholder='Ingredient'>";
    var newIngredient = document.createElement("div");
    newIngredient.setAttribute("class", "cookbook-create-ingredient");
    newIngredient.innerHTML = quantity + " " + ingredient;
    ingredientsDiv.appendChild(newIngredient);
}

function addStepField() {
    stepNum++;
    var stepLabel = "<label>Step " + stepNum + ":</label>";
    var stepInput = "<input type='text' name='instruction[" + stepNum + "][]'>";
    var newStep = document.createElement("div");
    newStep.setAttribute("class", "cookbook-create-step");
    newStep.innerHTML = stepLabel + " " + stepInput;
    stepsDiv.appendChild(newStep);
}

function addTagField() {
    var newTag = document.createElement("div");
    newTag.setAttribute("class", "cookbook-create-tag");
    newTag.innerHTML = tag;
    tagsDiv.appendChild(newTag);
}

function validateIngredients() {
    var ingredients = ingredientsDiv.children;

    for (var i = 0; i < ingredientNum; i++) {
        var quantity = ingredients[i].children[0].value;
        var ingredient = ingredients[i].children[1].value;

        // return false if one is filled in and the other one is empty
        if ((quantity === "" && ingredient !== "") || (quantity !== "" && ingredient === "")) {
            return false;
        }
    }

    // return true if both are filled out or both empty (for all ingredient sets)
    return true;
}

function submitForm() {
    if (titleInput.value === "") {
        alert("Please enter a title for the recipe.");
    } else if (firstIngredient.value === "") {
        alert("Please enter at least one ingredient (i.e. first ingredient cannot be empty)");
    } else if (firstStep.value === "") {
        alert("Please enter at least one step (i.e. Step 1 cannot be empty)");
    } else if (!validateIngredients()) {
        alert("Please make sure all ingredients have both a quantity and an ingredient.")
    } else {
        document.forms["cookbook-create-form"].submit();
    }
}