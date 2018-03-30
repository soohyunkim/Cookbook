let titleInput;
let description;

document.addEventListener("DOMContentLoaded", function(event) {
    titleInput = document.getElementById("cookbook-create-title");
    description = document.getElementById("cookbook-create-description");
});

function submitCookbookForm() {
  if (titleInput.value === "") {
      alert("Please enter a title for the cookbook.");
  } else if (description.value === "") {
      alert("Please enter a description of the cookbook.");
  } else {
      document.forms["cookbook-create-form"].submit();
  }
}
