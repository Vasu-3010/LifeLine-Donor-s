<?php
header('Content-Type: application/json');
require_once "db.php";

// Function to get all donors
function getAllDonors() {
    global $conn;
    $donors = array();
    $result = $conn->query("SELECT * FROM donors ORDER BY datetime DESC");
    while ($row = $result->fetch_assoc()) {
        $donors[] = $row;
    }
    return $donors;
}

// Function to add a new donor
function addDonor($name, $age, $mobile, $hospital, $datetime) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO donors (name, age, mobile, hospital, datetime) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $name, $age, $mobile, $hospital, $datetime);
    return $stmt->execute();
}

// Function to update a donor
function updateDonor($id, $name, $age, $mobile, $hospital, $datetime) {
    global $conn;
    $stmt = $conn->prepare("UPDATE donors SET name=?, age=?, mobile=?, hospital=?, datetime=? WHERE id=?");
    $stmt->bind_param("sisssi", $name, $age, $mobile, $hospital, $datetime, $id);
    return $stmt->execute();
}

// Function to delete a donor
function deleteDonor($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM donors WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Handle different actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'get_all':
            echo json_encode(['success' => true, 'data' => getAllDonors()]);
            break;

        case 'add':
            if (isset($_POST['name'], $_POST['age'], $_POST['mobile'], $_POST['hospital'], $_POST['datetime'])) {
                $success = addDonor(
                    $_POST['name'],
                    $_POST['age'],
                    $_POST['mobile'],
                    $_POST['hospital'],
                    $_POST['datetime']
                );
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            }
            break;

        case 'update':
            if (isset($_POST['id'], $_POST['name'], $_POST['age'], $_POST['mobile'], $_POST['hospital'], $_POST['datetime'])) {
                $success = updateDonor(
                    $_POST['id'],
                    $_POST['name'],
                    $_POST['age'],
                    $_POST['mobile'],
                    $_POST['hospital'],
                    $_POST['datetime']
                );
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            }
            break;

        case 'delete':
            if (isset($_POST['id'])) {
                $success = deleteDonor($_POST['id']);
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing donor ID']);
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