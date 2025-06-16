<?php
ob_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/home/www/yoursite.dx.am/eccps_website/php_errors.log');
error_reporting(E_ALL);

header('Content-Type: application/json');

$db_host = 'fdb1030.awardspace.net';
$db_port = 3306;
$db_user = '4631913_commentsdb';
$db_pass = '12345678k11';
$db_name = '4631913_commentsdb';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

if ($conn->connect_error) {
    error_log('Database connection failed: ' . $conn->connect_error);
    ob_end_clean();
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$result = $conn->query('SELECT name, comment FROM comments ORDER BY created_at DESC');
if ($result === false) {
    error_log('Query failed: ' . $conn->error);
    ob_end_clean();
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

ob_end_clean();
echo json_encode($comments);

$conn->close();
?>