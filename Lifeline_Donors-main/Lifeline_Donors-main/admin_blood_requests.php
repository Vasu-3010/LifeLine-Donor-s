<?php
// admin_blood_requests.php
require_once 'db1.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle AJAX status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    try {
        $order_id = intval($_POST['order_id']);
        $status = $_POST['status'];
        
        // Validate status
        $valid_statuses = ['Approved', 'Rejected', 'Pending'];
        if (!in_array($status, $valid_statuses)) {
            throw new Exception("Invalid status value");
        }
        
        // First check if the order exists
        $check_stmt = $conn->prepare("SELECT order_id, status FROM blood_orders WHERE order_id = ?");
        $check_stmt->execute([$order_id]);
        $order = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            throw new Exception("Order not found");
        }
        
        if ($order['status'] !== 'Pending') {
            throw new Exception("This request has already been processed");
        }
        
        // Update the status
        $update_stmt = $conn->prepare("UPDATE blood_orders SET status = ?, updated_at = NOW() WHERE order_id = ?");
        $result = $update_stmt->execute([$status, $order_id]);
        
        if ($result) {
            // Get the updated order details
            $get_order = $conn->prepare("SELECT * FROM blood_orders WHERE order_id = ?");
            $get_order->execute([$order_id]);
            $updated_order = $get_order->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'message' => 'Status updated successfully',
                'order' => $updated_order
            ]);
        } else {
            throw new Exception("Failed to update status");
        }
    } catch(Exception $e) {
        error_log("Error updating status: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// Fetch all blood requests with status
try {
    $stmt = $conn->query("SELECT * FROM blood_orders ORDER BY order_date DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching blood requests: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Blood Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; }
        .container { max-width: 1200px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        h1 { text-align: center; color: #b71c1c; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #d32f2f; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { 
            padding: 8px 16px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold; 
            margin: 0 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .approve { 
            background: #43a047; 
            color: #fff; 
        }
        .approve:hover:not(:disabled) { 
            background: #2e7d32; 
        }
        .reject { 
            background: #e53935; 
            color: #fff; 
        }
        .reject:hover:not(:disabled) { 
            background: #c62828; 
        }
        .pending { color: #ff9800; font-weight: bold; }
        .approved { color: #43a047; font-weight: bold; }
        .rejected { color: #e53935; font-weight: bold; }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
            display: inline-block;
            min-width: 100px;
        }
        .status-pending { background: #fff3e0; color: #ff9800; }
        .status-approved { background: #e8f5e9; color: #43a047; }
        .status-rejected { background: #ffebee; color: #e53935; }
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            display: none;
            z-index: 1000;
            animation: slideIn 0.5s ease-out;
        }
        .toast.success { background: #43a047; }
        .toast.error { background: #e53935; }
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Blood Requests</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Hospital</th>
                    <th>A+</th><th>A-</th><th>B+</th><th>B-</th><th>AB+</th><th>AB-</th><th>O+</th><th>O-</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <?php
                $status = $order['status'] ?? '';
                $displayStatus = $status === '' ? 'Pending' : $status;
                ?>
                <tr id="row-<?= $order['order_id'] ?>">
                    <td><?= $order['order_id'] ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['mobile_number']) ?></td>
                    <td><?= htmlspecialchars($order['hospital_name']) ?></td>
                    <td><?= $order['a_plus'] ?></td>
                    <td><?= $order['a_minus'] ?></td>
                    <td><?= $order['b_plus'] ?></td>
                    <td><?= $order['b_minus'] ?></td>
                    <td><?= $order['ab_plus'] ?></td>
                    <td><?= $order['ab_minus'] ?></td>
                    <td><?= $order['o_plus'] ?></td>
                    <td><?= $order['o_minus'] ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td>
                        <span class="status-badge status-<?= strtolower($displayStatus) ?>">
                            <?= $displayStatus ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($status === '' || strtolower($status) === 'pending'): ?>
                            <button class="btn approve" onclick="updateStatus(<?= $order['order_id'] ?>, 'Approved')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn reject" onclick="updateStatus(<?= $order['order_id'] ?>, 'Rejected')">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admindashboard.html" style="text-decoration:none;color:#d32f2f;font-weight:bold;">&larr; Back to Dashboard</a>
    </div>

    <div id="toast" class="toast"></div>

    <script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast ${type}`;
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    function updateStatus(orderId, status) {
        if (!confirm('Are you sure you want to ' + status.toLowerCase() + ' this request?')) {
            return;
        }

        const row = document.getElementById('row-' + orderId);
        const buttons = row.querySelectorAll('.btn');

        // Disable buttons and show loading state
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('loading');
        });

        const formData = new FormData();
        formData.append('order_id', orderId);
        formData.append('status', status);

        fetch('admin_blood_requests.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.order) {
                const newStatus = data.order.status || status; // Use status from DB if available
                const statusCell = row.querySelector('td:nth-last-child(2)');
                const actionCell = row.querySelector('td:last-child');

                // Update status badge
                statusCell.innerHTML = `
                    <span class="status-badge status-${newStatus.toLowerCase()}">
                        ${newStatus}
                    </span>
                `;

                // Update action buttons
                actionCell.innerHTML = '<span>-</span>';

                showToast('Status updated successfully!');
            } else {
                // Re-enable buttons
                buttons.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('loading');
                });

                showToast(data.error || 'Failed to update status.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Re-enable buttons
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('loading');
            });

            showToast('Failed to update status. Please try again.', 'error');
        });
    }
    </script>
</body>
</html> 