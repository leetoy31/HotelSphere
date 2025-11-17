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
      overflow: hidden;
      box-shadow: var(--shadow-soft);
    }
    
    .bookings__table table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .bookings__table th {
      background: var(--gradient-gold);
      color: var(--white);
      padding: 1rem;
      text-align: left;
      font-weight: 600;
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
    
    .no__bookings {
      text-align: center;
      padding: 3rem;
      color: var(--text-light);
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
                </tr>
              </thead>
              <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
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
                    <span class="status__badge status__confirmed">
                      <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                    </span>
                  </td>
                  <td><?php echo date('M d, Y H:i', strtotime($row['booking_date'])); ?></td>
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
</body>
</html>