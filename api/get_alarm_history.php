<?php
// api/get_alarm_history.php
include 'config.php';

// Get alarm events with pagination
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM alarm_events";
$countResult = mysqli_query($conn, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];

// Get paginated data
$query = "SELECT id, reason, triggered_by, triggered_at, status, stopped_at 
          FROM alarm_events 
          ORDER BY triggered_at DESC 
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

$response = [
    'total' => $totalRows,
    'rows' => $rows
];

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>
