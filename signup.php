<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input values
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Your secret key
    $secretKey = "6Lfb4WYqAAAAAAltasZMo1zcKv8QEiLXIKWFNZ0A";

    // Verify reCAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo "Please complete the reCAPTCHA.";
    } else {
        // If reCAPTCHA is successful, show thank you message
        echo "Thank you for signing up, " . htmlspecialchars($name) . "!";
    }
} else {
    // If not a POST request, throw 405 error
    http_response_code(405);
    echo "405 Method Not Allowed";
}
?>

