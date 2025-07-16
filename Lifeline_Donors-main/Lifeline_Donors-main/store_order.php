<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lifeline_donors";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get POST data
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $hospital = $_POST['hospital'];
    $quantities = json_decode($_POST['quantities'], true);

    if (!is_array($quantities)) {
        echo json_encode(['success' => false, 'error' => 'Quantities not decoded properly', 'raw' => $_POST['quantities']]);
        exit();
    }

    // Prepare SQL statement
    $sql = "INSERT INTO blood_orders (customer_name, mobile_number, hospital_name, 
            a_plus, a_minus, b_plus, b_minus, ab_plus, ab_minus, o_plus, o_minus, 
            order_date) 
            VALUES (:name, :mobile, :hospital, 
            :a_plus, :a_minus, :b_plus, :b_minus, :ab_plus, :ab_minus, :o_plus, :o_minus, 
            NOW())";

    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':hospital', $hospital);
    $stmt->bindParam(':a_plus', $quantities['A+']);
    $stmt->bindParam(':a_minus', $quantities['A-']);
    $stmt->bindParam(':b_plus', $quantities['B+']);
    $stmt->bindParam(':b_minus', $quantities['B-']);
    $stmt->bindParam(':ab_plus', $quantities['AB+']);
    $stmt->bindParam(':ab_minus', $quantities['AB-']);
    $stmt->bindParam(':o_plus', $quantities['O+']);
    $stmt->bindParam(':o_minus', $quantities['O-']);

    // Execute the statement
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn = null;
?> 