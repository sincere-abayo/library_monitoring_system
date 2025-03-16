<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_monitor";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the latest sensor data
$query = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $data = mysqli_fetch_assoc($result);
  
  // Format the data for the dashboard
  $response = array(
    "temperature" => floatval($data["temperature"]),
    "humidity" => floatval($data["humidity"]),
    "light" => intval($data["light_level"]),
    "sound" => intval($data["sound_level"]),
    "gas" => intval($data["gas_level"]),
    "fan_status" => (bool)$data["fan_status"],
    "led_status" => (bool)$data["led_status"],
    "timestamp" => $data["timestamp"]
  );
} 

else {
  // No data found, return defaults
  $response = array(
    "temperature" => 0,
    "humidity" => 0,
    "light" => 0,
    "sound" => 0,
    "gas" => 0,
    "fan_status" => false,
    "led_status" => false,
    "timestamp" => date("Y-m-d H:i:s")
  );
}
// Add to your existing get_sensor_data.php response
$settingsQuery = "SELECT alarm_active, alarm_reason FROM system_settings LIMIT 1";
$settingsResult = mysqli_query($conn, $settingsQuery);
if ($settingsResult && mysqli_num_rows($settingsResult) > 0) {
    $settings = mysqli_fetch_assoc($settingsResult);
    $response["alarm_active"] = (bool)$settings["alarm_active"];
    $response["alarm_reason"] = $settings["alarm_reason"];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close connection
$conn->close();
?>
