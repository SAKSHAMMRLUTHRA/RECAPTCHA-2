<?php
// Include database connection
include 'db.php';

// Handle POST request (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for username availability
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        
        // Query to check if username exists
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        
        // Respond based on availability
        if (mysqli_num_rows($result) > 0) {
            echo "Username is taken";
        } else {
            echo "Username is available";
        }
    } 
    // Handle user registration and reCAPTCHA validation
    elseif (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $recaptchaResponse = $_POST['g-recaptcha-response'];

        // reCAPTCHA validation
        $secretKey = "6Ld-VGYqAAAAAMVNlDlVhgvBal2ebI0CLA3PKMhH";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            // If reCAPTCHA fails
            echo "Please complete the reCAPTCHA correctly.";
        } else {
            // On successful reCAPTCHA, handle user registration
            // Assume you have a query to insert the new user into the database here (ensure $conn is your db connection)
            $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Hash password
            $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$passwordHash')";
            
            if (mysqli_query($conn, $insertQuery)) {
                // Show a thank you message on successful sign-up
                echo "Thank you for signing up, " . htmlspecialchars($name) . "!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
