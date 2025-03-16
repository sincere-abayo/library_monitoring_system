<?php
include 'config.php';

// Check if system_settings table exists
$checkTable = "SHOW TABLES LIKE 'system_settings'";
$tableExists = mysqli_query($conn, $checkTable);

if (mysqli_num_rows($tableExists) == 0) {
    // Table doesn't exist, create it
    $createTable = "CREATE TABLE system_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        automation_mode BOOLEAN DEFAULT 0,
        temp_threshold FLOAT DEFAULT 28.0,
        light_threshold INT DEFAULT 800,
        sound_threshold INT DEFAULT 300,
        gas_threshold INT DEFAULT 100,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($conn, $createTable)) {
        // Insert default settings
        mysqli_query($conn, "INSERT INTO system_settings (automation_mode) VALUES (0)");
    } else {
        die("Error creating table: " . mysqli_error($conn));
    }
}

// Get current mode
$query = "SELECT automation_mode FROM system_settings LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $response = ['automation_mode' => intval($row['automation_mode'])];
} else {
    // No settings found, return default
    $response = ['automation_mode' => 0];
}

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>
