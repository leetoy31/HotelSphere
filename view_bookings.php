<?php
require_once 'config.php';

// Simple authentication - in production, use proper authentication
$authenticated = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if (!$authenticated && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $authenticated = true;
    }
}

if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header('Location: view_bookings.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="hotels-style.css" />
  <style>
    .admin__section {
      padding: 8rem 2rem 4rem;
      min-height: 100vh;
      background: var(--extra-light);
    }
    
    .login__form {
      max-width: 400px;
      margin: 0 auto;
      background: var(--white);
      padding: 2.5rem;
      border-radius: 1rem;
      box-shadow: var(--shadow-soft);
    }
    
    .login__form h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: var(--text-dark);
    }
    
    .login__form input {
      width: 100%;
      padding: 0.875rem 1rem;
      margin-bottom: 1rem;
      border: 2px solid var(--extra-light);
      border-radius: 0.5rem;
      font-size: 1rem;
    }
    
    .login__form button {
      width: 100%;
      padding: 1rem;
      background: var(--gradient-gold);
      color: var(--white);
      border: none;
      border-radius: 0.5rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
    }
    
    .admin__header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }
    
    .admin__header h1 {
      font-size: 2.5rem;
      color: var(--text-dark);
    }
    
    .btn__logout {
      padding: 0.75rem 1.5rem;
      background: #dc3545;
      color: var(--white);
      border: none;
      border-radius: 0.5rem;
      text-decoration: none;
      font-weight: 600;
    }
    
    .bookings__table {
      background: var(--white);
      border-radius: 1rem;
      overflow-x: auto;
      box-shadow: var(--shadow-soft);
    }
    
    .bookings__table table {
      width: 100%;
      border-collapse: collapse;
      min-width: 1200px;
    }
    
    .bookings__table th {
      background: var(--gradient-gold);
      color: var(--white);
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      white-space: nowrap;
    }
    
    .bookings__table td {
      padding: 1rem;
      border-bottom: 1px solid var(--extra-light);
      color: var(--text-dark);
    }
    
    .bookings__table tr:hover {
      background: var(--extra-light);
    }
    
    .status__badge {
      display: inline-block;
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    
    .status__confirmed {
      background: rgba(40, 167, 69, 0.1);
      color: #28a745;
    }
    
    .status__cancelled {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
    }
    
    .status__pending {
      background: rgba(255, 193, 7, 0.1);
      color: #ffc107;
    }
    
    .no__bookings {
      text-align: center;
      padding: 3rem;
      color: var(--text-light);
    }

    .action__buttons {
      display: flex;
      gap: 0.5rem;
    }

    .btn__edit, .btn__delete {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
    }

    .btn__edit {
      background: #17a2b8;
      color: var(--white);
    }

    .btn__edit:hover {
      background: #138496;
    }

    .btn__delete {
      background: #dc3545;
      color: var(--white);
    }

    .btn__delete:hover {
      background: #c82333;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }

    .modal.active {
      display: flex;
    }

    .modal__content {
      background: var(--white);
      border-radius: 1rem;
      max-width: 800px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modal__header {
      background: var(--gradient-gold);
      color: var(--white);
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 1rem 1rem 0 0;
    }

    .modal__header h2 {
      margin: 0;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .btn__close {
      background: none;
      border: none;
      color: var(--white);
      font-size: 1.5rem;
      cursor: pointer;
      padding: 0;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: background 0.3s ease;
    }

    .btn__close:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .modal__body {
      padding: 2rem;
    }

    .form__group {
      margin-bottom: 1.5rem;
    }

    .form__row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form__group label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    .form__group label i {
      color: var(--primary-color);
    }

    .form__group input,
    .form__group select,
    .form__group textarea {
      width: 100%;
      padding: 0.875rem 1rem;
      border: 2px solid var(--extra-light);
      border-radius: 0.5rem;
      font-size: 1rem;
      font-family: "Lato", sans-serif;
      transition: all 0.3s ease;
    }

    .form__group input:focus,
    .form__group select:focus,
    .form__group textarea:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(196, 156, 116, 0.1);
    }

    .form__group textarea {
      resize: vertical;
      min-height: 80px;
    }

    .modal__footer {
      padding: 1.5rem 2rem;
      border-top: 1px solid var(--extra-light);
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
    }

    .btn__cancel, .btn__save {
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 0.5rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn__cancel {
      background: var(--extra-light);
      color: var(--text-dark);
    }

    .btn__cancel:hover {
      background: #d0d0d0;
    }

    .btn__save {
      background: var(--gradient-gold);
      color: var(--white);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn__save:hover {
      box-shadow: 0 5px 15px rgba(196, 156, 116, 0.3);
    }

    .alert {
      padding: 1rem 1.5rem;
      border-radius: 0.5rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-weight: 500;
    }

    .alert__success {
      background: rgba(40, 167, 69, 0.1);
      color: #28a745;
      border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .alert__error {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      border: 1px solid rgba(220, 53, 69, 0.3);
    }

    @media (max-width: 768px) {
      .form__row {
        grid-template-columns: 1fr;
      }

      .modal__content {
        margin: 1rem;
      }

      .modal__footer {
        flex-direction: column;
      }

      .btn__cancel, .btn__save {
        width: 100%;
      }
    }
  </style>
  <title>Admin - View Bookings</title>
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
      <?php if ($authenticated): ?>
        <li><a href="view_bookings.php" class="active">BOOKINGS</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <section class="admin__section">
    <div class="container">
      
      <?php if (!$authenticated): ?>
        <!-- Login Form -->
        <form method="POST" class="login__form">
          <h2>Admin Login</h2>
          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit" name="login">Login</button>
          <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--text-light);">
            Default: admin / admin123
          </p>
        </form>
      <?php else: ?>
        <!-- Admin Panel -->
        <div class="admin__header">
          <h1>All Bookings</h1>
          <a href="view_bookings.php?logout" class="btn__logout">
            <i class="ri-logout-box-line"></i> Logout
          </a>
        </div>

        <div id="alertContainer"></div>

        <div class="bookings__table">
          <?php
          $conn = getDBConnection();
          $sql = "SELECT * FROM bookings ORDER BY booking_date DESC";
          $result = $conn->query($sql);
          
          if ($result && $result->num_rows > 0):
          ?>
            <table>
              <thead>
                <tr>
                  <th>Reference</th>
                  <th>Hotel</th>
                  <th>Guest Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Check-in</th>
                  <th>Check-out</th>
                  <th>Guests</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Booked On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="bookingsTableBody">
                <?php while($row = $result->fetch_assoc()): ?>
                <tr data-id="<?php echo $row['id']; ?>">
                  <td><strong><?php echo htmlspecialchars($row['booking_reference']); ?></strong></td>
                  <td><?php echo htmlspecialchars($row['hotel_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['guest_email']); ?></td>
                  <td><?php echo htmlspecialchars($row['guest_phone']); ?></td>
                  <td><?php echo date('M d, Y', strtotime($row['check_in_date'])); ?></td>
                  <td><?php echo date('M d, Y', strtotime($row['check_out_date'])); ?></td>
                  <td><?php echo htmlspecialchars($row['num_guests']); ?></td>
                  <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
                  <td>
                    <span class="status__badge status__<?php echo strtolower($row['status']); ?>">
                      <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                    </span>
                  </td>
                  <td><?php echo date('M d, Y H:i', strtotime($row['booking_date'])); ?></td>
                  <td>
                    <div class="action__buttons">
                      <button class="btn__edit" onclick="openEditModal(<?php echo $row['id']; ?>)">
                        <i class="ri-edit-line"></i> Edit
                      </button>
                      <button class="btn__delete" onclick="deleteBooking(<?php echo $row['id']; ?>)">
                        <i class="ri-delete-bin-line"></i> Delete
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="no__bookings">
              <i class="ri-folder-open-line" style="font-size: 4rem; color: var(--text-light);"></i>
              <p>No bookings found</p>
            </div>
          <?php endif; ?>
        </div>

        <?php
        $conn->close();
        ?>
      <?php endif; ?>

    </div>
  </section>

  <!-- Edit Booking Modal -->
  <div id="editModal" class="modal">
    <div class="modal__content">
      <div class="modal__header">
        <h2><i class="ri-edit-line"></i> Edit Booking</h2>
        <button class="btn__close" onclick="closeEditModal()">
          <i class="ri-close-line"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="editBookingForm">
          <input type="hidden" id="bookingId" name="id">
          
          <div class="form__group">
            <label><i class="ri-hotel-line"></i> Hotel Name</label>
            <input type="text" id="hotelName" readonly style="background: var(--extra-light);">
          </div>

          <div class="form__group">
            <label><i class="ri-user-line"></i> Guest Name *</label>
            <input type="text" id="guestName" name="guest_name" required>
          </div>

          <div class="form__row">
            <div class="form__group">
              <label><i class="ri-mail-line"></i> Email *</label>
              <input type="email" id="guestEmail" name="guest_email" required>
            </div>
            <div class="form__group">
              <label><i class="ri-phone-line"></i> Phone *</label>
              <input type="tel" id="guestPhone" name="guest_phone" required>
            </div>
          </div>

          <div class="form__row">
            <div class="form__group">
              <label><i class="ri-calendar-check-line"></i> Check-in Date *</label>
              <input type="date" id="checkIn" name="check_in" required>
            </div>
            <div class="form__group">
              <label><i class="ri-calendar-line"></i> Check-out Date *</label>
              <input type="date" id="checkOut" name="check_out" required>
            </div>
          </div>

          <div class="form__row">
            <div class="form__group">
              <label><i class="ri-group-line"></i> Number of Guests *</label>
              <select id="numGuests" name="num_guests" required>
                <option value="1">1 Guest</option>
                <option value="2">2 Guests</option>
                <option value="3">3 Guests</option>
                <option value="4">4 Guests</option>
                <option value="5">5+ Guests</option>
              </select>
            </div>
            <div class="form__group">
              <label><i class="ri-shield-check-line"></i> Status *</label>
              <select id="status" name="status" required>
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
          </div>

          <div class="form__group">
            <label><i class="ri-message-line"></i> Special Requests</label>
            <textarea id="specialRequests" name="special_requests" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal__footer">
        <button class="btn__cancel" onclick="closeEditModal()">Cancel</button>
        <button class="btn__save" onclick="saveBooking()">
          <i class="ri-save-line"></i> Save Changes
        </button>
      </div>
    </div>
  </div>

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
    function openEditModal(bookingId) {
      // Fetch booking details
      fetch(`update_booking.php?id=${bookingId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const booking = data.booking;
            
            // Populate form
            document.getElementById('bookingId').value = booking.id;
            document.getElementById('hotelName').value = booking.hotel_name;
            document.getElementById('guestName').value = booking.guest_name;
            document.getElementById('guestEmail').value = booking.guest_email;
            document.getElementById('guestPhone').value = booking.guest_phone;
            document.getElementById('checkIn').value = booking.check_in_date;
            document.getElementById('checkOut').value = booking.check_out_date;
            document.getElementById('numGuests').value = booking.num_guests;
            document.getElementById('status').value = booking.status;
            document.getElementById('specialRequests').value = booking.special_requests || '';
            
            // Show modal
            document.getElementById('editModal').classList.add('active');
          } else {
            showAlert(data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showAlert('Failed to load booking details', 'error');
        });
    }

    function closeEditModal() {
      document.getElementById('editModal').classList.remove('active');
    }

    function saveBooking() {
      const formData = {
        id: document.getElementById('bookingId').value,
        guest_name: document.getElementById('guestName').value,
        guest_email: document.getElementById('guestEmail').value,
        guest_phone: document.getElementById('guestPhone').value,
        check_in: document.getElementById('checkIn').value,
        check_out: document.getElementById('checkOut').value,
        num_guests: document.getElementById('numGuests').value,
        status: document.getElementById('status').value,
        special_requests: document.getElementById('specialRequests').value
      };

      fetch('update_booking.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showAlert('Booking updated successfully!', 'success');
          closeEditModal();
          
          // Update table row
          updateTableRow(formData, data);
        } else {
          showAlert(data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showAlert('Failed to update booking', 'error');
      });
    }

    function updateTableRow(formData, responseData) {
      const row = document.querySelector(`tr[data-id="${formData.id}"]`);
      if (row) {
        const cells = row.cells;
        cells[2].textContent = formData.guest_name;
        cells[3].textContent = formData.guest_email;
        cells[4].textContent = formData.guest_phone;
        cells[5].textContent = formatDate(formData.check_in);
        cells[6].textContent = formatDate(formData.check_out);
        cells[7].textContent = formData.num_guests;
        cells[8].textContent = '₱' + responseData.total_amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        // Update status badge
        const statusBadge = cells[9].querySelector('.status__badge');
        statusBadge.className = `status__badge status__${formData.status.toLowerCase()}`;
        statusBadge.textContent = formData.status.charAt(0).toUpperCase() + formData.status.slice(1);
      }
    }

    function deleteBooking(bookingId) {
      if (confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
        fetch('delete_booking.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ id: bookingId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showAlert('Booking deleted successfully!', 'success');
            // Remove row from table
            const row = document.querySelector(`tr[data-id="${bookingId}"]`);
            if (row) {
              row.remove();
            }
          } else {
            showAlert(data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showAlert('Failed to delete booking', 'error');
        });
      }
    }

    function showAlert(message, type) {
      const alertContainer = document.getElementById('alertContainer');
      const alert = document.createElement('div');
      alert.className = `alert alert__${type}`;
      alert.innerHTML = `
        <i class="ri-${type === 'success' ? 'checkbox-circle' : 'error-warning'}-line"></i>
        ${message}
      `;
      
      alertContainer.appendChild(alert);
      
      setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s ease';
        setTimeout(() => alert.remove(), 300);
      }, 3000);
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
    }

    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeEditModal();
      }
    });

    // Set minimum dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('checkIn').min = today;
    
    document.getElementById('checkIn').addEventListener('change', function() {
      const checkInDate = new Date(this.value);
      const minCheckOut = new Date(checkInDate);
      minCheckOut.setDate(minCheckOut.getDate() + 1);
      document.getElementById('checkOut').min = minCheckOut.toISOString().split('T')[0];
    });
  </script>
</body>
</html>
