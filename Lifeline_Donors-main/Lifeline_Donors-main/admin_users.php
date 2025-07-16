<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lifeline_donors";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Function to get all users
function getAllUsers() {
    global $conn;
    $users = array();
    $result = $conn->query("SELECT id, Username, EmailId, MobileNum, Address FROM registration ORDER BY id DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Function to add a new user
function addUser($username, $email, $mobile, $address, $password) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO registration (Username, EmailId, MobileNum, Address, Password) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssss", $username, $email, $mobile, $address, $hashedPassword);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Function to update a user
function updateUser($id, $username, $email, $mobile, $address) {
    global $conn;
    $stmt = $conn->prepare("UPDATE registration SET Username=?, EmailId=?, MobileNum=?, Address=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("ssssi", $username, $email, $mobile, $address, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Function to update user password
function updatePassword($id, $newPassword) {
    global $conn;
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE registration SET Password=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("si", $hashedPassword, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Function to delete a user
function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM registration WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return false;
}

// Handle different actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'get_all':
            $users = getAllUsers();
            echo json_encode(['success' => true, 'data' => $users]);
            break;

        case 'add':
            if (isset($_POST['username'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['password'])) {
                $success = addUser(
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['mobile'],
                    $_POST['address'],
                    $_POST['password']
                );
                echo json_encode([
                    'success' => $success,
                    'message' => $success ? 'User added successfully' : 'Failed to add user'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            }
            break;

        case 'update':
            if (isset($_POST['id'], $_POST['username'], $_POST['email'], $_POST['mobile'], $_POST['address'])) {
                $success = updateUser(
                    $_POST['id'],
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['mobile'],
                    $_POST['address']
                );
                echo json_encode([
                    'success' => $success,
                    'message' => $success ? 'User updated successfully' : 'Failed to update user'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            }
            break;

        case 'update_password':
            if (isset($_POST['id'], $_POST['new_password'])) {
                $success = updatePassword($_POST['id'], $_POST['new_password']);
                echo json_encode([
                    'success' => $success,
                    'message' => $success ? 'Password updated successfully' : 'Failed to update password'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            }
            break;

        case 'delete':
            if (isset($_POST['id'])) {
                $success = deleteUser($_POST['id']);
                echo json_encode([
                    'success' => $success,
                    'message' => $success ? 'User deleted successfully' : 'Failed to delete user'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No action specified']);
}

$conn->close();
?> 