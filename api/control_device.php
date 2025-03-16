<?php
include 'config.php';

// Check if in automation mode
$modeQuery = "SELECT automation_mode FROM system_settings LIMIT 1";
$modeResult = mysqli_query($conn, $modeQuery);

if ($modeResult && mysqli_num_rows($modeResult) > 0) {
    $modeRow = mysqli_fetch_assoc($modeResult);
    $automationMode = intval($modeRow['automation_mode']);
    
    // If in automation mode, don't allow manual control
    if ($automationMode == 1) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'System is in automation mode']);
        exit;
    }
}

// Process device control
if (isset($_POST['device']) && isset($_POST['status'])) {
    $device = $_POST['device'];
    $status = intval($_POST['status']);
    
    // Validate device
    if ($device != 'fan' && $device != 'led') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid device']);
        exit;
    }
    
    // First, check if the device_status table exists, if not create it
    $checkTableQuery = "SHOW TABLES LIKE 'device_status'";
    $tableExists = mysqli_query($conn, $checkTableQuery);
    
    if (mysqli_num_rows($tableExists) == 0) {
        // Create the device_status table
        $createTableQuery = "CREATE TABLE device_status (
            id INT AUTO_INCREMENT PRIMARY KEY,
            device_name VARCHAR(50) NOT NULL,
            status INT NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if (!mysqli_query($conn, $createTableQuery)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to create device_status table: ' . mysqli_error($conn)]);
            exit;
        }
    }
    
    // Check if there's an existing record for this device
    $checkDeviceQuery = "SELECT id FROM device_status WHERE device_name = '$device' ORDER BY id DESC LIMIT 1";
    $deviceResult = mysqli_query($conn, $checkDeviceQuery);
    
    if ($deviceResult && mysqli_num_rows($deviceResult) > 0) {
        // Update existing record
        $deviceRow = mysqli_fetch_assoc($deviceResult);
        $deviceId = $deviceRow['id'];
        
        $updateQuery = "UPDATE device_status SET status = $status, timestamp = CURRENT_TIMESTAMP WHERE id = $deviceId";
        
        if (mysqli_query($conn, $updateQuery)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to update device status: ' . mysqli_error($conn)]);
        }
    } else {
        // Insert new record
        $insertQuery = "INSERT INTO device_status (device_name, status) VALUES ('$device', $status)";
        
        if (mysqli_query($conn, $insertQuery)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to insert device status: ' . mysqli_error($conn)]);
        }
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}

mysqli_close($conn);
?>
