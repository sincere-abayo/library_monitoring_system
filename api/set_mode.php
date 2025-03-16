<?php
include 'config.php';

// Create system_settings table if it doesn't exist
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

// Update mode
if (isset($_POST['automation_mode'])) {
    $mode = intval($_POST['automation_mode']);
    $query = "UPDATE system_settings SET automation_mode = $mode";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing automation_mode parameter']);
}

mysqli_close($conn);
?>
