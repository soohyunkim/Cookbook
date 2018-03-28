let titleInput;

document.addEventListener("DOMContentLoaded", function(event) {
    titleInput = document.getElementById("cookbook-create-title");
});

function submitCookbookForm() {
  if (titleInput.value === "") {
      alert("Please enter a title for the cookbook.");
  } else {
      document.forms["cookbook-create-form"].submit();
  }
}
