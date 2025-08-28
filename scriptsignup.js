// Toggle Password Visibility
function togglePassword1(fieldId) {
    var passwordField = document.getElementById(fieldId);
    if (passwordField.type === "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}

// Validate Password Confirmation
function validateForm() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    var errorMsg = document.getElementById("error-msg");

    if (password !== confirmPassword) {
        errorMsg.innerText = "❌ Passwords do not match!";
        return false;
    }
    return true;
}
