// Load hotel data from sessionStorage
document.addEventListener('DOMContentLoaded', function() {
  const hotelDataStr = sessionStorage.getItem('selectedHotel');
  
  if (!hotelDataStr) {
    alert('No hotel selected. Redirecting to hotels page...');
    window.location.href = 'hotels.html';
    return;
  }

  const hotelData = JSON.parse(hotelDataStr);
  
  // Populate hotel summary
  document.getElementById('hotelImage').src = hotelData.image;
  document.getElementById('hotelName').textContent = hotelData.name;
  document.getElementById('hotelLocation').innerHTML = `<i class="ri-map-pin-line"></i> ${hotelData.location}`;
  document.getElementById('hotelRating').textContent = hotelData.rating;
  document.getElementById('pricePerNight').textContent = `₱${parseFloat(hotelData.price).toLocaleString()}`;

  // Populate hidden form fields
  document.getElementById('hotelNameInput').value = hotelData.name;
  document.getElementById('hotelPriceInput').value = hotelData.price;
  document.getElementById('hotelLocationInput').value = hotelData.location;
  document.getElementById('hotelRatingInput').value = hotelData.rating;
  document.getElementById('hotelImageInput').value = hotelData.image;

  // Set minimum dates
  const today = new Date();
  const tomorrow = new Date(today);
  tomorrow.setDate(tomorrow.getDate() + 1);
  
  const checkInInput = document.getElementById('checkIn');
  const checkOutInput = document.getElementById('checkOut');
  
  checkInInput.min = today.toISOString().split('T')[0];
  checkOutInput.min = tomorrow.toISOString().split('T')[0];

  // Calculate total on date change
  function calculateTotal() {
    const checkIn = checkInInput.value;
    const checkOut = checkOutInput.value;
    
    if (checkIn && checkOut) {
      const checkInDate = new Date(checkIn);
      const checkOutDate = new Date(checkOut);
      const timeDiff = checkOutDate - checkInDate;
      const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
      
      if (nights > 0) {
        const pricePerNight = parseFloat(hotelData.price);
        const total = pricePerNight * nights;
        
        document.getElementById('numNights').textContent = nights;
        document.getElementById('totalAmount').textContent = `₱${total.toLocaleString()}`;
        
        // Update hidden fields
        document.getElementById('nightsInput').value = nights;
        document.getElementById('totalInput').value = total;
      } else {
        document.getElementById('numNights').textContent = '0';
        document.getElementById('totalAmount').textContent = '₱0';
        document.getElementById('nightsInput').value = '0';
        document.getElementById('totalInput').value = '0';
      }
    }
  }

  checkInInput.addEventListener('change', function() {
    const checkInDate = new Date(this.value);
    const minCheckOut = new Date(checkInDate);
    minCheckOut.setDate(minCheckOut.getDate() + 1);
    checkOutInput.min = minCheckOut.toISOString().split('T')[0];
    
    if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
      checkOutInput.value = '';
    }
    
    calculateTotal();
  });

  checkOutInput.addEventListener('change', calculateTotal);

  // Form submission
  const bookingForm = document.getElementById('bookingForm');
  bookingForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const checkIn = checkInInput.value;
    const checkOut = checkOutInput.value;
    
    if (!checkIn || !checkOut) {
      alert('Please select check-in and check-out dates');
      return;
    }

    const checkInDate = new Date(checkIn);
    const checkOutDate = new Date(checkOut);
    
    if (checkOutDate <= checkInDate) {
      alert('Check-out date must be after check-in date');
      return;
    }

    // Calculate nights and total
    const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
    const total = parseFloat(hotelData.price) * nights;

    // Update hidden fields before submitting
    document.getElementById('nightsInput').value = nights;
    document.getElementById('totalInput').value = total;

    // Store booking data in sessionStorage for confirmation page
    const bookingData = {
      hotelName: hotelData.name,
      hotelPrice: hotelData.price,
      hotelLocation: hotelData.location,
      hotelRating: hotelData.rating,
      hotelImage: hotelData.image,
      guestName: document.getElementById('guestName').value,
      guestEmail: document.getElementById('guestEmail').value,
      guestPhone: document.getElementById('guestPhone').value,
      checkIn: checkIn,
      checkOut: checkOut,
      numGuests: document.getElementById('numGuests').value,
      specialRequests: document.getElementById('specialRequests').value,
      nights: nights,
      totalAmount: total
    };

    sessionStorage.setItem('bookingData', JSON.stringify(bookingData));

    // Submit the form
    this.submit();
  });
});

// Phone number formatting
document.getElementById('guestPhone').addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.startsWith('63')) {
    value = value.substring(2);
  }
  if (value.startsWith('0')) {
    value = value.substring(1);
  }
  if (value.length > 10) {
    value = value.substring(0, 10);
  }
  
  if (value.length >= 3) {
    e.target.value = '+63 ' + value.substring(0, 3) + (value.length > 3 ? ' ' + value.substring(3, 6) : '') + (value.length > 6 ? ' ' + value.substring(6) : '');
  } else {
    e.target.value = value ? '+63 ' + value : '';
  }
});