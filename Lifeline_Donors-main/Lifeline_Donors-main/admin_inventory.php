<?php
header('Content-Type: application/json');

// Function to read CSV file
function readInventoryData() {
    $inventory = [];
    if (($handle = fopen("Hospitals.csv", "r")) !== FALSE) {
        // Skip header row
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            $inventory[] = [
                'district' => $data[0],
                'city' => $data[1],
                'hospital' => $data[2],
                'A+' => (int)$data[3],
                'A-' => (int)$data[4],
                'B+' => (int)$data[5],
                'B-' => (int)$data[6],
                'AB+' => (int)$data[7],
                'AB-' => (int)$data[8],
                'O+' => (int)$data[9],
                'O-' => (int)$data[10]
            ];
        }
        fclose($handle);
    }
    return $inventory;
}

// Function to write CSV file
function writeInventoryData($inventory) {
    if (($handle = fopen("Hospitals.csv", "w")) !== FALSE) {
        // Write header
        fputcsv($handle, ['District', 'City', 'Hospital Name', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
        
        // Write data
        foreach ($inventory as $row) {
            fputcsv($handle, [
                $row['district'],
                $row['city'],
                $row['hospital'],
                $row['A+'],
                $row['A-'],
                $row['B+'],
                $row['B-'],
                $row['AB+'],
                $row['AB-'],
                $row['O+'],
                $row['O-']
            ]);
        }
        fclose($handle);
        return true;
    }
    return false;
}

// Handle different actions
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'get_all':
        $inventory = readInventoryData();
        echo json_encode(['success' => true, 'data' => $inventory]);
        break;
        
    case 'update':
        $inventory = readInventoryData();
        $hospital = $_POST['hospital'];
        $bloodType = $_POST['bloodType'];
        $quantity = (int)$_POST['quantity'];
        
        foreach ($inventory as &$row) {
            if ($row['hospital'] === $hospital) {
                $row[$bloodType] = $quantity;
                break;
            }
        }
        
        if (writeInventoryData($inventory)) {
            echo json_encode(['success' => true, 'message' => 'Inventory updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update inventory']);
        }
        break;
        
    case 'add_hospital':
        $inventory = readInventoryData();
        $newHospital = [
            'district' => $_POST['district'],
            'city' => $_POST['city'],
            'hospital' => $_POST['hospital'],
            'A+' => (int)$_POST['A+'],
            'A-' => (int)$_POST['A-'],
            'B+' => (int)$_POST['B+'],
            'B-' => (int)$_POST['B-'],
            'AB+' => (int)$_POST['AB+'],
            'AB-' => (int)$_POST['AB-'],
            'O+' => (int)$_POST['O+'],
            'O-' => (int)$_POST['O-']
        ];
        
        $inventory[] = $newHospital;
        
        if (writeInventoryData($inventory)) {
            echo json_encode(['success' => true, 'message' => 'Hospital added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add hospital']);
        }
        break;
        
    case 'delete_hospital':
        $inventory = readInventoryData();
        $hospital = $_POST['hospital'];
        
        foreach ($inventory as $key => $row) {
            if ($row['hospital'] === $hospital) {
                unset($inventory[$key]);
                break;
            }
        }
        
        $inventory = array_values($inventory); // Re-index array
        
        if (writeInventoryData($inventory)) {
            echo json_encode(['success' => true, 'message' => 'Hospital deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete hospital']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?> 