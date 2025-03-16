<?php
// api/stop_alarm.php
include 'config.php';

// Update the system_settings table
$query = "UPDATE system_settings SET alarm_active = 0, alarm_reason = NULL";
if (mysqli_query($conn, $query)) {
    // Update the latest alarm event
    $updateQuery = "UPDATE alarm_events SET status = 'stopped', stopped_at = NOW() WHERE status = 'active' ORDER BY id DESC LIMIT 1";
    mysqli_query($conn, $updateQuery);
    
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_close($conn);
?>
