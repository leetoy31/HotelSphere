<?php
require_once 'config.php';

// This file handles AJAX requests to store hotel data in PHP session
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['hotelData'])) {
        $_SESSION['selectedHotel'] = $input['hotelData'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No hotel data provided']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>