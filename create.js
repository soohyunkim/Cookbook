var ingredientsDiv;
var stepsDiv;
var tagsDiv;

var stepNum = 1;

var tag = "<input type'text' class='tag' name='tag'>";
var quantity = "<input type='text' class='ingredient_qty' name='ingredient_qty' placeholder='Quantity'>";
var ingredient = "<input type='text' class='ingredient_desc' name='ingredient_desc' placeholder='Ingredient'>";

document.addEventListener("DOMContentLoaded", function(event) {
    ingredientsDiv = document.getElementById("cookbook-create-ingredients");
    stepsDiv = document.getElementById("cookbook-create-steps");
    tagsDiv = document.getElementById("cookbook-create-tags");
});

function addIngredientField() {
    var newIngredient = document.createElement("div");
    newIngredient.setAttribute("class", "cookbook-create-ingredient");
    newIngredient.innerHTML = quantity + " " + ingredient;
    ingredientsDiv.appendChild(newIngredient);
}

function addStepField() {
    stepNum++;
    var stepLabel = "<label>Step " + stepNum + ":</label>";
    var stepInput = "<input type='text' name='step-" + stepNum + "'>";
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