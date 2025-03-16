<?php
include 'config.php';

// Check if the device_status table exists
$checkTableQuery = "SHOW TABLES LIKE 'device_status'";
$tableExists = mysqli_query($conn, $checkTableQuery);

if (mysqli_num_rows($tableExists) > 0) {
    // Get the latest status for fan and LED from device_status table
    $fanQuery = "SELECT status FROM device_status WHERE device_name = 'fan' ORDER BY timestamp DESC LIMIT 1";
    $ledQuery = "SELECT status FROM device_status WHERE device_name = 'led' ORDER BY timestamp DESC LIMIT 1";
    
    $fanResult = mysqli_query($conn, $fanQuery);
    $ledResult = mysqli_query($conn, $ledQuery);
    
    $fanStatus = false;
    $ledStatus = false;
    
    if ($fanResult && mysqli_num_rows($fanResult) > 0) {
        $fanRow = mysqli_fetch_assoc($fanResult);
        $fanStatus = (bool)$fanRow['status'];
    }
    
    if ($ledResult && mysqli_num_rows($ledResult) > 0) {
        $ledRow = mysqli_fetch_assoc($ledResult);
        $ledStatus = (bool)$ledRow['status'];
    }
    
    $response = [
        'fan_status' => $fanStatus,
        'led_status' => $ledStatus
    ];
} else {
    // If device_status table doesn't exist, fall back to the old method
    
        // No data found, return defaults
        $response = [
            'fan_status' => false,
            'led_status' => false
        ];
}

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>
