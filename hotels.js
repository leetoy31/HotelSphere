// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// Book Now button functionality - Redirect to checkout page
const bookButtons = document.querySelectorAll('.btn__book');

bookButtons.forEach(button => {
  button.addEventListener('click', async function() {
    // Disable button to prevent double-clicks
    this.disabled = true;
    this.innerHTML = '<i class="ri-loader-4-line"></i> Processing...';
    
    const hotelCard = this.closest('.hotel__item');
    const hotelName = hotelCard.querySelector('h3').textContent;
    const hotelPrice = hotelCard.querySelector('.price').textContent.replace('₱', '').replace(',', '');
    const hotelLocation = hotelCard.querySelector('.hotel__location').textContent.trim();
    const hotelRating = hotelCard.querySelector('.hotel__badge').textContent;
    const hotelImage = hotelCard.querySelector('.hotel__image img').src;
    
    // Store hotel data in sessionStorage
    const hotelData = {
      name: hotelName,
      price: hotelPrice,
      location: hotelLocation,
      rating: hotelRating,
      image: hotelImage
    };
    
    sessionStorage.setItem('selectedHotel', JSON.stringify(hotelData));
    
    // Also send to PHP session
    try {
      const response = await fetch('save_hotel_session.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ hotelData: hotelData })
      });
      
      const result = await response.json();
      
      if (result.success) {
        // Redirect to checkout page
        window.location.href = 'checkout.php';
      } else {
        alert('Error: ' + result.message);
        this.disabled = false;
        this.innerHTML = 'Book Now';
      }
    } catch (error) {
      console.error('Error:', error);
      // If PHP session fails, still try to proceed with sessionStorage
      console.log('PHP session failed, using sessionStorage fallback');
      window.location.href = 'checkout.php';
    }
  });
});

// Add hover effect to hotel cards
const hotelItems = document.querySelectorAll('.hotel__item');

hotelItems.forEach(item => {
  item.addEventListener('mouseenter', function() {
    this.style.transform = 'translateY(-10px)';
  });
  
  item.addEventListener('mouseleave', function() {
    this.style.transform = 'translateY(0)';
  });
});

// Animation on scroll for hotel cards
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, observerOptions);

// Initially hide cards for animation
hotelItems.forEach(item => {
  item.style.opacity = '0';
  item.style.transform = 'translateY(30px)';
  item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  observer.observe(item);
});

// Console log for debugging
console.log('Hotels page loaded successfully');
console.log(`Total hotels displayed: ${hotelItems.length}`);