<<<<<<< HEAD
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Not logged in']));
}

// Database configuration
$host = "localhost";  // Default for XAMPP/WAMP
$user = "root";       // Default username
$password = "";       // Default password (empty)
$dbname = "lookblood"; // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

// Get order data
$hospital_name = $conn->real_escape_string($_POST['hospital_name'] ?? '');
$a_plus = intval($_POST['a_plus'] ?? 0);
$a_minus = intval($_POST['a_minus'] ?? 0);
$b_plus = intval($_POST['b_plus'] ?? 0);
$b_minus = intval($_POST['b_minus'] ?? 0);
$ab_plus = intval($_POST['ab_plus'] ?? 0);
$ab_minus = intval($_POST['ab_minus'] ?? 0);
$o_plus = intval($_POST['o_plus'] ?? 0);
$o_minus = intval($_POST['o_minus'] ?? 0);
$user_id = intval($_SESSION['user_id']);

// Insert order into database
$sql = "INSERT INTO blood_orders (hospital_name, a_plus, a_minus, b_plus, b_minus, ab_plus, ab_minus, o_plus, o_minus, user_id) 
        VALUES ('$hospital_name', $a_plus, $a_minus, $b_plus, $b_minus, $ab_plus, $ab_minus, $o_plus, $o_minus, $user_id)";

if ($conn->query($sql)) {
    echo json_encode(['status' => 'success', 'message' => 'Order saved successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error saving order: ' . $conn->error]);
}

$conn->close();
=======
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Not logged in']));
}

// Database configuration
$host = "localhost";  // Default for XAMPP/WAMP
$user = "root";       // Default username
$password = "";       // Default password (empty)
$dbname = "lookblood"; // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

// Get order data
$hospital_name = $conn->real_escape_string($_POST['hospital_name'] ?? '');
$a_plus = intval($_POST['a_plus'] ?? 0);
$a_minus = intval($_POST['a_minus'] ?? 0);
$b_plus = intval($_POST['b_plus'] ?? 0);
$b_minus = intval($_POST['b_minus'] ?? 0);
$ab_plus = intval($_POST['ab_plus'] ?? 0);
$ab_minus = intval($_POST['ab_minus'] ?? 0);
$o_plus = intval($_POST['o_plus'] ?? 0);
$o_minus = intval($_POST['o_minus'] ?? 0);
$user_id = intval($_SESSION['user_id']);

// Insert order into database
$sql = "INSERT INTO blood_orders (hospital_name, a_plus, a_minus, b_plus, b_minus, ab_plus, ab_minus, o_plus, o_minus, user_id) 
        VALUES ('$hospital_name', $a_plus, $a_minus, $b_plus, $b_minus, $ab_plus, $ab_minus, $o_plus, $o_minus, $user_id)";

if ($conn->query($sql)) {
    echo json_encode(['status' => 'success', 'message' => 'Order saved successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error saving order: ' . $conn->error]);
}

$conn->close();
>>>>>>> 026d5fd6dc40ce5d459676ab65cb05fd1d2cf482
?>