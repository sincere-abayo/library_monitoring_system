<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Explicitly set variables first
$temperature = isset($_POST["temperature"]) ? floatval($_POST["temperature"]) : 0;
$humidity = isset($_POST["humidity"]) ? floatval($_POST["humidity"]) : 0;
$light_level = isset($_POST["light_level"]) ? intval($_POST["light_level"]) : 0;
$sound_level = isset($_POST["sound_level"]) ? intval($_POST["sound_level"]) : 0;
$gas_level = isset($_POST["gas_level"]) ? intval($_POST["gas_level"]) : 0;
$fan_status = isset($_POST["fan_status"]) ? intval($_POST["fan_status"]) : 0;
$led_status = isset($_POST["led_status"]) ? intval($_POST["led_status"]) : 0;

// Use a simple INSERT query instead of prepared statement for debugging
$query = "INSERT INTO sensor_data (temperature, humidity, light_level, sound_level, gas_level) 
          VALUES ($temperature, $humidity, $light_level, $sound_level, $gas_level)";

if ($conn->query($query) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

$conn->close();
?>
