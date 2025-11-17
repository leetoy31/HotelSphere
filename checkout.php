<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="hotels-style.css" />
  <link rel="stylesheet" href="checkout-style.css" />
  <title>Checkout - Royal Sphere</title>
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

  <section class="checkout__hero">
    <div class="container">
      <h1>Complete Your Booking</h1>
      <p>You're just one step away from your dream vacation</p>
    </div>
  </section>

  <section class="checkout__section">
    <div class="container">
      <div class="checkout__grid">
        
        <!-- Booking Form -->
        <div class="checkout__form">
          <h2>Guest Information</h2>
          <form id="bookingForm" action="process_booking.php" method="POST">
            <div class="form__group">
              <label for="guestName"><i class="ri-user-line"></i> Full Name *</label>
              <input type="text" id="guestName" name="guestName" required placeholder="Enter your full name">
            </div>

            <div class="form__group">
              <label for="guestEmail"><i class="ri-mail-line"></i> Email Address *</label>
              <input type="email" id="guestEmail" name="guestEmail" required placeholder="your.email@example.com">
            </div>

            <div class="form__group">
              <label for="guestPhone"><i class="ri-phone-line"></i> Phone Number *</label>
              <input type="tel" id="guestPhone" name="guestPhone" required placeholder="+63 XXX XXX XXXX">
            </div>

            <div class="form__row">
              <div class="form__group">
                <label for="checkIn"><i class="ri-calendar-check-line"></i> Check-in Date *</label>
                <input type="date" id="checkIn" name="checkIn" required>
              </div>

              <div class="form__group">
                <label for="checkOut"><i class="ri-calendar-line"></i> Check-out Date *</label>
                <input type="date" id="checkOut" name="checkOut" required>
              </div>
            </div>

            <div class="form__group">
              <label for="numGuests"><i class="ri-group-line"></i> Number of Guests *</label>
              <select id="numGuests" name="numGuests" required>
                <option value="">Select number of guests</option>
                <option value="1">1 Guest</option>
                <option value="2">2 Guests</option>
                <option value="3">3 Guests</option>
                <option value="4">4 Guests</option>
                <option value="5">5+ Guests</option>
              </select>
            </div>

            <div class="form__group">
              <label for="specialRequests"><i class="ri-message-line"></i> Special Requests</label>
              <textarea id="specialRequests" name="specialRequests" rows="4" placeholder="Any special requests or requirements? (Optional)"></textarea>
            </div>

            <!-- Hidden fields to pass hotel data to PHP -->
            <input type="hidden" id="hotelNameInput" name="hotelName">
            <input type="hidden" id="hotelPriceInput" name="hotelPrice">
            <input type="hidden" id="hotelLocationInput" name="hotelLocation">
            <input type="hidden" id="hotelRatingInput" name="hotelRating">
            <input type="hidden" id="hotelImageInput" name="hotelImage">
            <input type="hidden" id="nightsInput" name="nights">
            <input type="hidden" id="totalInput" name="totalAmount">

            <div class="form__terms">
              <input type="checkbox" id="terms" name="terms" required>
              <label for="terms">I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></label>
            </div>

            <button type="submit" class="btn__submit">
              <i class="ri-lock-line"></i> Proceed to Payment
            </button>
          </form>
        </div>

        <!-- Booking Summary -->
        <div class="checkout__summary">
          <h2>Booking Summary</h2>
          <div class="summary__card">
            <div class="summary__hotel">
              <img id="hotelImage" src="" alt="Hotel">
              <div class="summary__hotel-info">
                <h3 id="hotelName"></h3>
                <p id="hotelLocation"><i class="ri-map-pin-line"></i></p>
                <div class="summary__rating" id="hotelRating"></div>
              </div>
            </div>

            <div class="summary__details">
              <div class="summary__row">
                <span>Price per night:</span>
                <span class="summary__price" id="pricePerNight">₱0</span>
              </div>
              <div class="summary__row">
                <span>Number of nights:</span>
                <span id="numNights">0</span>
              </div>
              <div class="summary__divider"></div>
              <div class="summary__row summary__total">
                <span>Total Amount:</span>
                <span class="summary__price" id="totalAmount">₱0</span>
              </div>
            </div>

            <div class="summary__features">
              <h4>Included Amenities:</h4>
              <ul>
                <li><i class="ri-wifi-line"></i> Free WiFi</li>
                <li><i class="ri-restaurant-line"></i> Breakfast Included</li>
                <li><i class="ri-customer-service-2-line"></i> 24/7 Support</li>
                <li><i class="ri-close-circle-line"></i> Free Cancellation</li>
              </ul>
            </div>
          </div>

          <div class="security__badge">
            <i class="ri-shield-check-line"></i>
            <div>
              <strong>Secure Booking</strong>
              <p>Your information is protected</p>
            </div>
          </div>
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

  <script src="checkout.js"></script>
</body>
</html>