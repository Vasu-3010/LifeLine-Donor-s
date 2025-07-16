<?php
require_once 'db1.php';

try {
    // Get count of blood requests
    $stmt = $conn->query("SELECT COUNT(*) as count FROM blood_orders");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['count' => $result['count']]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to get count: ' . $e->getMessage()]);
}
?> 