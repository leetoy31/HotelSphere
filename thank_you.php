<?php
require_once 'config.php';

// Check if booking confirmation exists
if (!isset($_SESSION['booking_confirmation'])) {
    header('Location: hotels.html');
    exit();
}

$booking = $_SESSION['booking_confirmation'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="hotels-style.css" />
  <link rel="stylesheet" href="thankyou-style.css" />
  <title>Booking Confirmed - Royal Sphere</title>
</head>
<body>
  <nav>
    <div class="nav__header">
      <div class="nav__logo">
        <a href="index.html">R.S.</a>
      </div>
    </div>
    <ul class="nav__links">
      <li><a href="index.html">HOME</a></li>
      <li><a href="hotels.html">HOTELS</a></li>
      <li><a href="index.html#contact">CONTACT</a></li>
    </ul>
  </nav>

  <section class="thankyou__section">
    <div class="container">
      <div class="thankyou__content">
        
        <!-- Success Animation -->
        <div class="success__animation">
          <div class="success__circle">
            <i class="ri-check-line"></i>
          </div>
        </div>

        <!-- Main Message -->
        <div class="thankyou__message">
          <h1>Booking Confirmed!</h1>
          <p>Thank you for choosing Royal Sphere. Your reservation has been successfully processed.</p>
        </div>

        <!-- Booking Reference -->
        <div class="booking__reference">
          <div class="reference__label">Booking Reference</div>
          <div class="reference__code"><?php echo htmlspecialchars($booking['booking_reference']); ?></div>
          <p class="reference__note">Please save this reference number for your records</p>
        </div>

        <!-- Booking Details Card -->
        <div class="confirmation__card">
          <div class="card__header">
            <h2><i class="ri-file-list-3-line"></i> Booking Details</h2>
          </div>

          <div class="booking__summary">
            <!-- Hotel Info -->
            <div class="summary__section">
              <div class="section__icon">
                <i class="ri-hotel-line"></i>
              </div>
              <div class="section__content">
                <h3><?php echo htmlspecialchars($booking['hotel_name']); ?></h3>
                <p><i class="ri-map-pin-line"></i> <?php echo htmlspecialchars($booking['hotel_location']); ?></p>
                <span class="rating__badge"><?php echo htmlspecialchars($booking['hotel_rating']); ?></span>
              </div>
            </div>

            <!-- Guest Info -->
            <div class="summary__section">
              <div class="section__icon">
                <i class="ri-user-line"></i>
              </div>
              <div class="section__content">
                <h3>Guest Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['guest_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['guest_email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['guest_phone']); ?></p>
                <p><strong>Number of Guests:</strong> <?php echo htmlspecialchars($booking['num_guests']); ?></p>
              </div>
            </div>

            <!-- Stay Details -->
            <div class="summary__section">
              <div class="section__icon">
                <i class="ri-calendar-check-line"></i>
              </div>
              <div class="section__content">
                <h3>Stay Details</h3>
                <p><strong>Check-in:</strong> <?php echo date('F d, Y', strtotime($booking['check_in'])); ?></p>
                <p><strong>Check-out:</strong> <?php echo date('F d, Y', strtotime($booking['check_out'])); ?></p>
                <p><strong>Duration:</strong> <?php echo $booking['nights']; ?> night(s)</p>
              </div>
            </div>

            <!-- Payment Summary -->
            <div class="summary__section payment__section">
              <div class="section__icon">
                <i class="ri-money-dollar-circle-line"></i>
              </div>
              <div class="section__content">
                <h3>Payment Summary</h3>
                <div class="payment__row">
                  <span>Price per night:</span>
                  <span>₱<?php echo number_format($booking['price_per_night'], 2); ?></span>
                </div>
                <div class="payment__row">
                  <span>Number of nights:</span>
                  <span><?php echo $booking['nights']; ?></span>
                </div>
                <div class="payment__divider"></div>
                <div class="payment__row payment__total">
                  <span>Total Amount Paid:</span>
                  <span>₱<?php echo number_format($booking['total_amount'], 2); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Next Steps -->
        <div class="next__steps">
          <h2><i class="ri-lightbulb-line"></i> What's Next?</h2>
          <div class="steps__grid">
            <div class="step__item">
              <div class="step__icon">
                <i class="ri-mail-line"></i>
              </div>
              <h3>Check Your Email</h3>
              <p>A confirmation email has been sent to your email address with all booking details.</p>
            </div>
            <div class="step__item">
              <div class="step__icon">
                <i class="ri-smartphone-line"></i>
              </div>
              <h3>Stay Connected</h3>
              <p>Our team will contact you soon to confirm your reservation and answer any questions.</p>
            </div>
            <div class="step__item">
              <div class="step__icon">
                <i class="ri-suitcase-line"></i>
              </div>
              <h3>Prepare for Your Trip</h3>
              <p>Start planning your activities and get ready for an amazing stay at <?php echo htmlspecialchars($booking['hotel_name']); ?>!</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="action__buttons">
          <button onclick="window.print()" class="btn__secondary">
            <i class="ri-printer-line"></i> Print Confirmation
          </button>
          <a href="hotels.html" class="btn__primary">
            <i class="ri-home-line"></i> Back to Hotels
          </a>
        </div>

        <!-- Contact Support -->
        <div class="support__section">
          <p>Need help? Contact our support team at <a href="mailto:support@royalsphere.com">support@royalsphere.com</a> or call <a href="tel:+63123456789">+63 123 456 789</a></p>
        </div>

      </div>
    </div>
  </section>

  <footer>
    <div class="footer__content">
      <p>© 2025 Royal Sphere - IM101-PROJECT. All rights reserved.</p>
      <div class="footer__links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Contact Us</a>
      </div>
    </div>
  </footer>

  <script>
    // Add animation on page load
    document.addEventListener('DOMContentLoaded', function() {
      const successCircle = document.querySelector('.success__circle');
      setTimeout(() => {
        successCircle.classList.add('animated');
      }, 100);
    });
  </script>
</body>
</html>