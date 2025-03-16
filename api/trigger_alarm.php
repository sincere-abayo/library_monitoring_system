<?php
// api/trigger_alarm.php
include 'config.php';

// Validate input
if (!isset($_POST['reason'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing reason parameter']);
    exit;
}

$reason = mysqli_real_escape_string($conn, $_POST['reason']);

// Update the system_settings table
$query = "UPDATE system_settings SET alarm_active = 1, alarm_reason = '$reason', alarm_timestamp = NOW()";
if (mysqli_query($conn, $query)) {
    // Insert an event in the alarm_events table
    $eventQuery = "INSERT INTO alarm_events (reason, triggered_by, status) VALUES ('$reason', 'user', 'active')";
    mysqli_query($conn, $eventQuery);
    
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_close($conn);
?>
