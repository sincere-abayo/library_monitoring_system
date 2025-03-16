<?php
include 'config.php';

// Get pagination parameters
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 15;

// Calculate offset
$offset = ($page - 1) * $limit;

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM sensor_data";
$countResult = mysqli_query($conn, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];

// Get the data with pagination
if ($limit > 0) {
    $query = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT $offset, $limit";
} else {
    // Show all records if limit is 0
    $query = "SELECT * FROM sensor_data ORDER BY timestamp DESC";
}

$result = mysqli_query($conn, $query);

$rows = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');

if ($limit > 0) {
    // Return with pagination info
    echo json_encode(array(
        'total' => $totalRows,
        'page' => $page,
        'limit' => $limit,
        'rows' => $rows
    ));
} else {
    // Return just the rows for "show all"
    echo json_encode($rows);
}

// Close connection
$conn->close(); 
?>
