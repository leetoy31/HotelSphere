<?php
require_once 'config.php';

// Check authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $bookingId = intval($input['id']);
    
    if ($bookingId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
        exit();
    }
    
    $conn = getDBConnection();
    
    // Delete booking
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Booking deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Booking not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete booking: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>