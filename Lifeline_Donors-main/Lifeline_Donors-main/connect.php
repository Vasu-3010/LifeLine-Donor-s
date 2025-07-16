<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Form not submitted');
}

// Collect and sanitize POST data  
$Username = sanitizeInput($_POST['Username'] ?? '');
$EmailId = sanitizeInput($_POST['EmailId'] ?? '');
$MobileNum = sanitizeInput($_POST['MobileNum'] ?? '');
$Address = sanitizeInput($_POST['Address'] ?? '');
$Password = $_POST['Password'] ?? '';
$ConfirmPassword = $_POST['ConfirmPassword'] ?? '';

// Validation flags and error messages array
$errors = [];

// 1. Username validation
if (empty($Username)) {
    $errors[] = "Username is required.";
} elseif (strlen($Username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
}

// 2. Email validation
if (empty($EmailId)) {
    $errors[] = "Email ID is required.";
} elseif (!filter_var($EmailId, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

// 3. Mobile number validation (exactly 10 digits)
if (empty($MobileNum)) {
    $errors[] = "Mobile number is required.";
} elseif (!preg_match("/^[0-9]{10}$/", $MobileNum)) {
    $errors[] = "Mobile number must be exactly 10 digits.";
}

// 4. Address validation
if (empty($Address)) {
    $errors[] = "Address is required.";
}

// 5. Password validation
if (empty($Password)) {
    $errors[] = "Password is required.";
} elseif (strlen($Password) < 6) {
    $errors[] = "Password must be at least 6 characters long.";
}

// 6. Confirm Password validation
if (empty($ConfirmPassword)) {
    $errors[] = "Confirm Password is required.";
} elseif ($Password !== $ConfirmPassword) {
    $errors[] = "Passwords do not match.";
}

// 7. Display errors if any
if (!empty($errors)) {
    echo "<div style='color: red; font-weight: bold;'>";
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
    echo "</div>";
    exit();
}

// If all validations pass, hash the password
$hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'lifeline_donors');

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Prepare the SQL statement to insert data
$stmt = $conn->prepare("INSERT INTO registration (Username, EmailId, MobileNum, Address, Password) VALUES (?, ?, ?, ?, ?)");

if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

// Bind parameters
$stmt->bind_param("sssss", $Username, $EmailId, $MobileNum, $Address, $hashedPassword);
// Change this section:
if ($stmt->execute()) {
    header("Location: loginpage.html?success=1");
    exit();
}

?>