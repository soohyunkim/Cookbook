var ingredientBgEl;
var ingredientModalEl;
var ingredientCurrentEl;

document.addEventListener("DOMContentLoaded", function(event) {
    ingredientBgEl = document.getElementById("ingredient-modal-background");
    ingredientModalEl = document.getElementById("ingredient-modal");
})

function onIngredientClick(ingredient) {
    if (ingredientBgEl.classList.contains("hide")) {
        ingredientBgEl.classList.remove("hide");
    }
    var ingId = "info-" + ingredient.replace(/\s/g, "-");
    ingredientCurrentEl = document.getElementById(ingId);
    ingredientCurrentEl.classList.add("active");
}

function onIngredientClose() {
    ingredientBgEl.classList.add("hide");
    if (ingredientCurrentEl.classList.contains("active")) {
        ingredientCurrentEl.classList.remove("active");
    }
}