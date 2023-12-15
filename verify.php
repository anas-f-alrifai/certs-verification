<?php

// Database connection details
define('DB_HOST', 'sql104.infinityfree.com');
define('DB_USER', 'epiz_33465101');
define('DB_PASSWORD', '8Password23');
define('DB_DATABASE', 'epiz_33465101_login');

// Get the ID from the request
$certificateID = isset($_GET['id']) ? $_GET['id'] : null;

// Validate input
if (!$certificateID) {
    respondError('Invalid ID');
}

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

// Check the connection
if ($conn->connect_error) {
    respondError('Database connection failed');
}

// Prepare and execute the query
$stmt = $conn->prepare("SELECT * FROM certs WHERE id = ?");
$stmt->bind_param("s", $certificateID);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the certificate exists
if ($result->num_rows > 0) {
    // Certificate found
    $certificateData = $result->fetch_assoc();
    respondSuccess(['certificate' => $certificateData]);
} else {
    // Certificate not found
    respondError('Certificate not found', 404);
}

// Close the database connection
$stmt->close();
$conn->close();

// Helper function to respond with success
function respondSuccess($data) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true] + $data);
    exit;
}

// Helper function to respond with error
function respondError($message, $statusCode = 400) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}
?>
