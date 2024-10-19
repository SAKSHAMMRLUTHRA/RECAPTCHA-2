<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $secretKey = "6Ld-VGYqAAAAAMVNlDlVhgvBal2ebI0CLA3PKMhH";
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo "Please complete the reCAPTCHA.";
    } else {
        // 
        echo "Thank you for signing up, " . htmlspecialchars($name) . "!";
    }
}
?>
<?php
// Assuming you have a database connection in db.php
include 'db.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    
    // Query to check if username exists
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "taken";
    } else {
        echo "available";
    }
}
?>

