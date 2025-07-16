<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lifeline_donors";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user = $_POST['username'];
$email = $_POST['email'];
$message = $_POST['message'];

// Prepare and execute insert
$stmt = $conn->prepare("INSERT INTO contact_messages (username, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $user, $email, $message);

if ($stmt->execute()) {
    echo "
    <script>
        alert('Message sent successfully!');
        window.location.href = ' Home Page Code.html';
    </script>
    ";
} else {
    echo "
    <script>
        alert('Failed to send message. Please try again.');
        window.history.back();
    </script>
    ";
}

$stmt->close();
$conn->close();
?>
