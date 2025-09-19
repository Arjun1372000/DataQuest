<?php
// config.php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ayur_trace_db');

// Connect to MySQL database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'DB connection failed: '.$conn->connect_error]));
}

// Set character set
$conn->set_charset("utf8");

// Helper function to sanitize input
function sanitize($data) {
    global $conn;
    if (is_array($data)) {
        foreach ($data as $k => $v) $data[$k] = sanitize($v);
        return $data;
    }
    return htmlspecialchars(stripslashes(trim($conn->real_escape_string($data))));
}
?>
