<?php
require_once 'config.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: hotels.html');
    exit();
}

// Get hotel data from either session or form (fallback)
$hotelData = null;

if (isset($_SESSION['selectedHotel'])) {
    $hotelData = $_SESSION['selectedHotel'];
} elseif (isset($_POST['hotelName']) && isset($_POST['hotelPrice'])) {
    // Fallback: construct hotel data from hidden form fields
    $hotelData = [
        'name' => $_POST['hotelName'],
        'price' => $_POST['hotelPrice'],
        'location' => $_POST['hotelLocation'] ?? 'Bohol',
        'rating' => $_POST['hotelRating'] ?? '4.5★',
        'image' => $_POST['hotelImage'] ?? 'assets/property-1.jpg'
    ];
} else {
    $_SESSION['error'] = 'Hotel information missing. Please select a hotel again.';
    header('Location: hotels.html');
    exit();
}

// Sanitize and validate input
$guestName = sanitizeInput($_POST['guestName']);
$guestEmail = sanitizeInput($_POST['guestEmail']);
$guestPhone = sanitizeInput($_POST['guestPhone']);
$checkIn = sanitizeInput($_POST['checkIn']);
$checkOut = sanitizeInput($_POST['checkOut']);
$numGuests = intval($_POST['numGuests']);
$specialRequests = sanitizeInput($_POST['specialRequests']);

// Validate required fields
if (empty($guestName) || empty($guestEmail) || empty($guestPhone) || empty($checkIn) || empty($checkOut) || empty($numGuests)) {
    $_SESSION['error'] = 'Please fill in all required fields';
    header('Location: checkout.php');
    exit();
}

// Validate email
if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: checkout.php');
    exit();
}

// Calculate nights and total amount
$checkInDate = new DateTime($checkIn);
$checkOutDate = new DateTime($checkOut);
$interval = $checkInDate->diff($checkOutDate);
$nights = $interval->days;

if ($nights <= 0) {
    $_SESSION['error'] = 'Check-out date must be after check-in date';
    header('Location: checkout.php');
    exit();
}

$hotelPrice = floatval($hotelData['price']);
$totalAmount = $hotelPrice * $nights;

// Generate booking reference
$bookingReference = generateBookingReference();

// Get database connection
$conn = getDBConnection();

// Prepare SQL statement
$sql = "INSERT INTO bookings (hotel_name, hotel_price, guest_name, guest_email, guest_phone, 
        check_in_date, check_out_date, num_guests, special_requests, total_amount, booking_reference) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $_SESSION['error'] = 'Database error occurred. Please try again.';
    header('Location: checkout.php');
    exit();
}

$stmt->bind_param("sdsssssisds", 
    $hotelData['name'], 
    $hotelPrice, 
    $guestName, 
    $guestEmail, 
    $guestPhone, 
    $checkIn, 
    $checkOut, 
    $numGuests, 
    $specialRequests, 
    $totalAmount, 
    $bookingReference
);

// Execute the statement
if ($stmt->execute()) {
    // Store booking details in session for confirmation page
    $_SESSION['booking_confirmation'] = [
        'booking_reference' => $bookingReference,
        'hotel_name' => $hotelData['name'],
        'hotel_location' => $hotelData['location'],
        'hotel_rating' => $hotelData['rating'],
        'hotel_image' => $hotelData['image'],
        'guest_name' => $guestName,
        'guest_email' => $guestEmail,
        'guest_phone' => $guestPhone,
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'num_guests' => $numGuests,
        'nights' => $nights,
        'price_per_night' => $hotelPrice,
        'total_amount' => $totalAmount,
        'booking_date' => date('Y-m-d H:i:s')
    ];
    
    // Clear selected hotel data
    unset($_SESSION['selectedHotel']);
    
    // Redirect to thank you page
    header('Location: thank_you.php');
    exit();
} else {
    $_SESSION['error'] = 'Booking failed: ' . $stmt->error . '. Please try again.';
    header('Location: checkout.php');
    exit();
}

$stmt->close();
$conn->close();
?>