<?php
// Database connection
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "";      // Your MySQL password (empty for XAMPP default)
$dbname = "lifeline_donors";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$newPassword = $data['newPassword'];

// Hash the new password (for security)
$newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

// Update query to change the password
$sql = "UPDATE registration SET password = ? WHERE username = ?";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $newPasswordHashed, $username);

if ($stmt->execute()) {
    // Return a success message
    echo json_encode(["success" => true, "message" => "Password updated successfully."]);
} else {
    // Return an error message
    echo json_encode(["success" => false, "message" => "Failed to update password."]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
