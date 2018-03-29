function detectPage() {
  var parts = document.location.pathname.split("/");
  var pageName = parts.pop() || parts.pop();
  return pageName.substring(0, pageName.length - 4);
}

function setActiveTab(page) {
  var menuEl;

  switch(page) {
    case "search":
      menuEl = document.getElementById("menu-search");
      menuEl.classList.add("active");
      break;
    case "cookbooks":
      menuEl = document.getElementById("menu-cookbooks");
      menuEl.classList.add("active");
      break;
    case "bookmarks":
      menuEl = document.getElementById("menu-bookmarks");
      menuEl.classList.add("active");
      break;
    case "create":
      menuEl = document.getElementById("menu-create");
      menuEl.classList.add("active");
      break;
    case "manageUsers":
      menuEl = document.getElementById("menu-manage-users");
      menuEl.classList.add("active");
      break;
    case "manageRecipes":
      menuEl = document.getElementById("menu-manage-recipes");
      menuEl.classList.add("active");
      break;
    case "manageIngredients":
      menuEl = document.getElementById("menu-manage-ingredients");
      menuEl.classList.add("active");
      break;
    default:
      break;
  }
}

document.addEventListener("DOMContentLoaded", function(event) {
  var page = detectPage();
  setActiveTab(page);
});