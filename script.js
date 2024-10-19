// Get DOM elements
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm-password');
const strengthStatus = document.getElementById('password-strength-status');
const strengthText = document.getElementById('password-strength-status-text');
const emailInput = document.getElementById("email");
const emailError = document.getElementById("email-error");
const usernameInput = document.getElementById("username");
const usernameMessage = document.getElementById("username-message");
const progressBar = document.getElementById("progress-bar");

// Regular expressions for password strength
const weakRegex = /^[a-zA-Z0-9]{1,6}$/; 
const mediumRegex = /^(?=.*[0-9])(?=.*[a-zA-Z]).{7,12}$/; 
const strongRegex = /^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[!@#\$%\^&\*]).{12,}$/;

// Add event listeners
passwordInput.addEventListener('input', checkPasswordStrength);
confirmPasswordInput.addEventListener('input', checkPasswordMatch);
emailInput.addEventListener("keyup", validateEmail);
usernameInput.addEventListener("keyup", checkUsernameAvailability);
document.querySelectorAll("input[required]").forEach(input => {
    input.addEventListener("keyup", updateProgressBar);
});

// Function to check password strength
function checkPasswordStrength() {
    const password = passwordInput.value;
    resetStrengthStatus();

    if (strongRegex.test(password)) {
        setStrengthStatus('Strong Password', 'strength-strong');
    } else if (mediumRegex.test(password)) {
        setStrengthStatus('Medium Strength Password', 'strength-medium');
    } else if (weakRegex.test(password)) {
        setStrengthStatus('Weak Password', 'strength-weak');
    }
}

// Reset strength status
function resetStrengthStatus() {
    strengthStatus.className = ''; 
    strengthText.innerHTML = '';
}

// Set strength status and text
function setStrengthStatus(text, className) {
    strengthStatus.classList.add(className);
    strengthText.innerHTML = text;
}

// Function to check if passwords match
function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const message = document.getElementById("confirm-message");

    if (password === confirmPassword) {
        message.style.color = "green";
        message.innerHTML = "Passwords match";
    } else {
        message.style.color = "red";
        message.innerHTML = "Passwords do not match";
    }
}

// Function to validate email format
function validateEmail() {
    const email = emailInput.value;
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (emailPattern.test(email)) {
        setEmailValidationMessage("Valid email address", "green");
    } else {
        setEmailValidationMessage("Invalid email address", "red");
    }
}

// Set email validation message
function setEmailValidationMessage(message, color) {
    emailError.style.color = color;
    emailError.innerHTML = message;
}

// Function to check username availability
function checkUsernameAvailability() {
    const username = usernameInput.value;
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "check_username.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.responseText === "taken") {
                setUsernameValidationMessage("Username is already taken", "red");
            } else {
                setUsernameValidationMessage("Username is available", "green");
            }
        }
    };
    xhr.send("username=" + encodeURIComponent(username));
}

// Set username validation message
function setUsernameValidationMessage(message, color) {
    usernameMessage.style.color = color;
    usernameMessage.innerHTML = message;
}

// Function to update progress bar
function updateProgressBar() {
    const totalFields = document.querySelectorAll("input[required]").length;
    const filledFields = Array.from(document.querySelectorAll("input[required]"))
        .filter(input => input.value !== "").length;

    const progressPercentage = (filledFields / totalFields) * 100;
    progressBar.style.width = progressPercentage + "%";
}
