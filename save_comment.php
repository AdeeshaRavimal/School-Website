<?php
// Start output buffering
ob_start();

// Set error handling to log but not display
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/home/www/centralc.atwebpages.com/eccps_website/php_errors.log');
error_reporting(E_ALL);

// Set content type to JSON
header('Content-Type: application/json');

// Database connection
$db_host = 'fdb1030.awardspace.net';
$db_port = 3306;
$db_user = '4631913_commentsdb';
$db_pass = '12345678k11';
$db_name = '4631913_commentsdb';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Check connection
if ($conn->connect_error) {
    error_log('Database connection failed: ' . $conn->connect_error);
    $response = ['error' => 'Database connection failed'];
    ob_end_clean();
    echo json_encode($response);
    exit;
}

// Get and sanitize input
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

if ($name && $comment) {
    $stmt = $conn->prepare('INSERT INTO comments (name, comment) VALUES (?, ?)');
    if ($stmt === false) {
        error_log('Prepare failed: ' . $conn->error);
        $response = ['error' => 'Query preparation failed: ' . $conn->error];
    } else {
        $stmt->bind_param('ss', $name, $comment);
        if ($stmt->execute()) {
            $response = ['success' => true];
        } else {
            error_log('Execute failed: ' . $stmt->error);
            $response = ['error' => 'Failed to save comment: ' . $stmt->error];
        }
        $stmt->close();
    }
} else {
    $response = ['error' => 'Invalid input'];
}

// Ensure all output is cleared before sending JSON
ob_end_clean();
echo json_encode($response);

$conn->close();
?>