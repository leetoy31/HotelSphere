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
    $guestName = sanitizeInput($input['guest_name']);
    $guestEmail = sanitizeInput($input['guest_email']);
    $guestPhone = sanitizeInput($input['guest_phone']);
    $checkIn = sanitizeInput($input['check_in']);
    $checkOut = sanitizeInput($input['check_out']);
    $numGuests = intval($input['num_guests']);
    $status = sanitizeInput($input['status']);
    $specialRequests = sanitizeInput($input['special_requests']);
    
    // Validate email
    if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit();
    }
    
    // Calculate nights and new total
    $checkInDate = new DateTime($checkIn);
    $checkOutDate = new DateTime($checkOut);
    $interval = $checkInDate->diff($checkOutDate);
    $nights = $interval->days;
    
    if ($nights <= 0) {
        echo json_encode(['success' => false, 'message' => 'Check-out date must be after check-in date']);
        exit();
    }
    
    $conn = getDBConnection();
    
    // Get hotel price
    $stmtPrice = $conn->prepare("SELECT hotel_price FROM bookings WHERE id = ?");
    $stmtPrice->bind_param("i", $bookingId);
    $stmtPrice->execute();
    $resultPrice = $stmtPrice->get_result();
    
    if ($resultPrice->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
        exit();
    }
    
    $hotelPrice = $resultPrice->fetch_assoc()['hotel_price'];
    $totalAmount = $hotelPrice * $nights;
    
    // Update booking
    $sql = "UPDATE bookings SET 
            guest_name = ?, 
            guest_email = ?, 
            guest_phone = ?, 
            check_in_date = ?, 
            check_out_date = ?, 
            num_guests = ?, 
            status = ?,
            special_requests = ?,
            total_amount = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssissdi", 
        $guestName, 
        $guestEmail, 
        $guestPhone, 
        $checkIn, 
        $checkOut, 
        $numGuests, 
        $status,
        $specialRequests,
        $totalAmount,
        $bookingId
    );
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Booking updated successfully',
            'nights' => $nights,
            'total_amount' => $totalAmount
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Get booking details
    $bookingId = intval($_GET['id']);
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        echo json_encode(['success' => true, 'booking' => $booking]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>