<?php
// api/get_alarm_status.php
include 'config.php';

$query = "SELECT alarm_active, alarm_reason FROM system_settings LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $response = [
        'alarm_active' => (bool)$row['alarm_active'],
        'alarm_reason' => $row['alarm_reason']
    ];
} else {
    $response = [
        'alarm_active' => false,
        'alarm_reason' => ''
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>

