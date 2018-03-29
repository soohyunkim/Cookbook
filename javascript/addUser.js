var emailInput;
var passwordInput;

document.addEventListener("DOMContentLoaded", function(event) {
    emailInput = document.getElementById("add-user-email");
    passwordInput = document.getElementById("add-user-password");
});

function validateEmail() {
    var regex = /\S+@\S+\.\S+/;
    return regex.test(emailInput.value);
}

function validateUserForm() {
    if (!validateEmail()) {
        alert("Please enter a valid email.");
    } else if (passwordInput.value.length < 4) {
        alert("Please enter a password that is at least 4 characters long.");
    } else {
        return true;
    }
    return false;
}