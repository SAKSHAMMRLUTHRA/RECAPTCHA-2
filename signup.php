<?php
// Include database connection
include 'db.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // reCAPTCHA secret key
    $secretKey = "6LfQXWYqAAAAAMkpYSQz_feWamZMT-aVwm2pLb3J"; // Replace with your actual secret key
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    // Check if reCAPTCHA verification was successful
    if (intval($responseKeys["success"]) !== 1) {
        echo "Please complete the reCAPTCHA.";
    } else {
        // Prepare SQL statement to insert user data
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

        // Bind parameters and execute the statement
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            // If successful, redirect to thank you page
            header('Location: thank_you.html');
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // Close statement and connection
        $stmt->close();
    }
}

// Check for username availability (keep this part if necessary)
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    
    // Query to check if username exists
    $query = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "taken";
    } else {
        echo "available";
    }
    
    // Close statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>



