<?php
// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and assign input values
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // reCAPTCHA secret key
    $secretKey = "6Ld-VGYqAAAAAMVNlDlVhgvBal2ebI0CLA3PKMhH";
    
    // Verify reCAPTCHA response
    $recaptchaURL = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse";
    $response = file_get_contents($recaptchaURL);
    $responseKeys = json_decode($response, true);

    // Check if reCAPTCHA was successful
    if (intval($responseKeys["success"]) !== 1) {
        echo "<script>alert('Please complete the reCAPTCHA.'); window.history.back();</script>";
    } else {
        // Connect to the database (ensure db.php includes the connection)
        include 'db.php';
        
        // Check if email or username already exists
        $checkUserQuery = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $checkUserQuery);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('This email is already registered.'); window.history.back();</script>";
        } else {
            // Hash the password and insert new user into the database
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            
            if (mysqli_query($conn, $insertQuery)) {
                echo "<script>alert('Thank you for signing up, $name!'); window.location.href = 'thankyou.html';</script>";
            } else {
                echo "<script>alert('An error occurred during registration. Please try again.'); window.history.back();</script>";
            }
        }
    }
} else {
    // Handle non-POST requests (405 error)
    http_response_code(405);
    echo "405 Method Not Allowed: Please submit the form using POST method.";
}
?>
