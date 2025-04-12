<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>

   <!-- Tailwind CSS CDN -->
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <!-- Animate.css CDN for animations -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

   <style>
      :root {
         --primary-color: #1e3a8a; /* Deep blue */
         --secondary-color: #3b82f6; /* Bright blue */
         --accent-color: #10b981; /* Green */
         --danger-color: #ef4444; /* Red */
         --warning-color: #f97316; /* Orange */
         --background-color: #f1f5f9; /* Light slate */
         --card-bg: #ffffff; /* White */
         --text-color: #1e293b; /* Dark slate */
         --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
         --transition: all 0.3s ease;
      }

      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         background-color: var(--background-color);
         font-family: 'Inter', sans-serif;
         color: var(--text-color);
         line-height: 1.6;
         margin-top: 80px; /* Adjust for fixed header */
      }

      .container {
         max-width: 1400px;
         margin: 0 auto;
         padding: 2rem;
      }

      .dashboard-header {
         text-align: center;
         margin-bottom: 2rem;
         animation: fadeInDown 0.5s ease-out;
      }

      .dashboard-header h1 {
         font-size: 2.5rem;
         font-weight: 700;
         color: var(--primary-color);
         margin-bottom: 0.5rem;
      }

      .dashboard-header p {
         color: #64748b;
         font-size: 1.1rem;
      }

      .stats-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
         gap: 1.5rem;
         margin-bottom: 3rem;
      }

      .stat-card {
         background: var(--card-bg);
         border-radius: 1rem;
         padding: 1.5rem;
         box-shadow: var(--shadow);
         display: flex;
         align-items: center;
         transition: var(--transition);
         animation: slideIn 0.5s ease-out;
      }

      .stat-card:hover {
         transform: translateY(-5px);
         box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      }

      .stat-icon {
         width: 60px;
         height: 60px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: 1.5rem;
         font-size: 1.8rem;
         color: white;
         transition: transform 0.3s ease;
      }

      .stat-card:hover .stat-icon {
         transform: scale(1.1);
      }

      .stat-card:nth-child(1) .stat-icon { background: var(--warning-color); }
      .stat-card:nth-child(2) .stat-icon { background: var(--accent-color); }
      .stat-card:nth-child(3) .stat-icon { background: var(--danger-color); }
      .stat-card:nth-child(4) .stat-icon { background: var(--secondary-color); }
      .stat-card:nth-child(5) .stat-icon { background: #8b5cf6; /* Purple */ }
      .stat-card:nth-child(6) .stat-icon { background: #ec4899; /* Pink */ }
      .stat-card:nth-child(7) .stat-icon { background: #6b7280; /* Gray */ }
      .stat-card:nth-child(8) .stat-icon { background: var(--primary-color); }

      .stat-info h3 {
         font-size: 1.8rem;
         font-weight: 600;
         color: var(--text-color);
         margin-bottom: 0.3rem;
      }

      .stat-info p {
         color: #64748b;
         font-size: 0.9rem;
         font-weight: 500;
      }

      .recent-activity {
         background: var(--card-bg);
         border-radius: 1rem;
         padding: 2rem;
         box-shadow: var(--shadow);
         animation: fadeInUp 0.5s ease-out;
      }

      .recent-activity h2 {
         font-size: 1.8rem;
         font-weight: 600;
         color: var(--primary-color);
         margin-bottom: 1.5rem;
      }

      .activity-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 1.5rem;
      }

      .activity-card {
         background: #f9fafb;
         border-radius: 0.75rem;
         padding: 1.5rem;
         border-left: 4px solid var(--secondary-color);
         transition: var(--transition);
         animation: slideIn 0.5s ease-out;
      }

      .activity-card:hover {
         background: #dbeafe;
         transform: translateY(-3px);
      }

      .activity-card i {
         font-size: 1.5rem;
         color: var(--secondary-color);
         margin-bottom: 1rem;
         display: inline-block;
         transition: transform 0.3s ease;
      }

      .activity-card:hover i {
         transform: scale(1.2);
      }

      .activity-card p {
         font-weight: 500;
         color: var(--text-color);
         margin-bottom: 0.5rem;
      }

      .activity-card span {
         color: #64748b;
         font-size: 0.8rem;
      }

      /* Admin Header Styles */
      .admin-header {
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
         box-shadow: var(--shadow);
         z-index: 1000;
         padding: 0 2rem;
         transition: var(--transition);
      }

      .header-container {
         display: flex;
         align-items: center;
         justify-content: space-between;
         max-width: 1400px;
         margin: 0 auto;
         height: 70px;
      }

      .logo {
         display: flex;
         align-items: center;
         text-decoration: none;
         transition: transform 0.3s ease;
      }

      .logo:hover {
         transform: translateX(5px);
      }

      .logo-icon {
         width: 40px;
         height: 40px;
         background: white;
         border-radius: 0.75rem;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: 12px;
         color: var(--primary-color);
         font-size: 1.5rem;
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      }

      .logo-text {
         display: flex;
         flex-direction: column;
      }

      .logo-main {
         font-size: 1.2rem;
         font-weight: 700;
         color: white;
         line-height: 1;
      }

      .logo-sub {
         font-size: 0.8rem;
         color: #dbeafe;
         font-weight: 500;
         letter-spacing: 1px;
      }

      .main-nav {
         flex: 1;
         margin: 0 2rem;
      }

      .nav-list {
         display: flex;
         list-style: none;
         margin: 0;
         padding: 0;
      }

      .nav-item {
         margin: 0 1rem;
      }

      .nav-link {
         display: flex;
         align-items: center;
         padding: 0.5rem 1rem;
         text-decoration: none;
         color: #dbeafe;
         border-radius: 0.5rem;
         transition: var(--transition);
         position: relative;
      }

      .nav-link i {
         margin-right: 8px;
         font-size: 1.1rem;
      }

      .nav-link:hover {
         color: white;
         background: rgba(255, 255, 255, 0.1);
      }

      .nav-link::before {
         content: '';
         position: absolute;
         bottom: 0;
         left: 0;
         width: 0;
         height: 2px;
         background: white;
         transition: width 0.3s ease;
      }

      .nav-link:hover::before {
         width: 100%;
      }

      .user-controls {
         display: flex;
         align-items: center;
      }

      .notification-bell {
         position: relative;
         margin-right: 1.5rem;
         cursor: pointer;
         color: #dbeafe;
         transition: var(--transition);
         font-size: 1.25rem;
      }

      .notification-bell:hover {
         color: white;
         transform: translateY(-2px);
      }

      .notification-count {
         position: absolute;
         top: -5px;
         right: -5px;
         background: var(--danger-color);
         color: white;
         width: 18px;
         height: 18px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 0.7rem;
         font-weight: bold;
      }

      .user-profile {
         display: flex;
         align-items: center;
         cursor: pointer;
         padding: 0.5rem;
         border-radius: 2rem;
         transition: var(--transition);
         background: rgba(255, 255, 255, 0.1);
      }

      .user-profile:hover {
         background: rgba(255, 255, 255, 0.2);
      }

      .avatar {
         width: 36px;
         height: 36px;
         background: white;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: 0.8rem;
         color: var(--primary-color);
         font-size: 1.25rem;
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      }

      .user-info {
         display: flex;
         flex-direction: column;
      }

      .user-name {
         font-weight: 600;
         font-size: 0.9rem;
         color: white;
      }

      .user-role {
         font-size: 0.7rem;
         color: #dbeafe;
      }

      .dropdown-arrow {
         margin-left: 0.5rem;
         font-size: 0.8rem;
         colorpedale: #dbeafe;
         transition: transform 0.3s ease;
      }

      .user-profile.active .dropdown-arrow {
         transform: rotate(180deg);
      }

      .user-dropdown {
         position: absolute;
         top: 70px;
         right: 2rem;
         width: 280px;
         background: white;
         border-radius: 1rem;
         box-shadow: var(--shadow);
         opacity: 0;
         visibility: hidden;
         transform: translateY(10px);
         transition: var(--transition);
         z-index: 1001;
      }

      .user-dropdown.active {
         opacity: 1;
         visibility: visible;
         transform: translateY(0);
      }

      .dropdown-header {
         padding: 1.5rem;
         display: flex;
         align-items: center;
         border-bottom: 1px solid #e2e8f0;
      }

      .dropdown-avatar {
         width: 50px;
         height: 50px;
         background: #dbeafe;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: 1rem;
         color: var(--primary-color);
         font-size: 2rem;
      }

      .dropdown-user-info h4 {
         margin: 0;
         font-size: 1rem;
         color: var(--text-color);
      }

      .dropdown-user-info p {
         margin: 0.2rem 0 0;
         font-size: 0.8rem;
         color: #64748b;
      }

      .dropdown-menu {
         padding: 0.5rem 0;
      }

      .dropdown-item {
         display: flex;
         align-items: center;
         padding: 0.8rem 1.5rem;
         text-decoration: none;
         color: #64748b;
         transition: var(--transition);
      }

      .dropdown-item i {
         margin-right: 1rem;
         font-size: 1rem;
         width: 20px;
         text-align: center;
      }

      .dropdown-item:hover {
         background: #f1f5f9;
         color: var(--primary-color);
         padding-left: 1.8rem;
      }

      .dropdown-divider {
         height: 1px;
         background: #e2e8f0;
         margin: 0.5rem 0;
      }

      .dropdown-item.logout {
         color: var(--danger-color);
      }

      .dropdown-item.logout:hover {
         background: #fee2e2;
         color: #dc2626;
      }

      /* Animations */
      @keyframes fadeInDown {
         from { opacity: 0; transform: translateY(-20px); }
         to { opacity: 1; transform: translateY(0); }
      }

      @keyframes fadeInUp {
         from { opacity: 0; transform: translateY(20px); }
         to { opacity: 1; transform: translateY(0); }
      }

      @keyframes slideIn {
         from { opacity: 0; transform: translateY(20px); }
         to { opacity: 1; transform: translateY(0); }
      }

      /* Responsive Design */
      @media (max-width: 992px) {
         .main-nav { display: none; }
         .header-container { padding: 0 1rem; }
         .stats-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
      }

      @media (max-width: 576px) {
         .logo-text { display: none; }
         .user-info { display: none; }
         .user-dropdown { width: 250px; right: 1rem; }
         .notification-bell { margin-right: 0.5rem; }
         .stat-card { flex-direction: column; text-align: center; }
         .stat-icon { margin-right: 0; margin-bottom: 1rem; }
         .dashboard-header h1 { font-size: 2rem; }
      }
   </style>
</head>
<body>
   
   <?php include 'admin_header.php'; ?>

   <div class="container mx-auto">
      <section class="dashboard">
         <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
         </div>

         <div class="stats-grid">
            <!-- Pending Payments -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-clock"></i>
               </div>
               <div class="stat-info">
                  <?php
                     $total_pendings = 0;
                     $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
                     if (mysqli_num_rows($select_pending) > 0) {
                        while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                           $total_pendings += $fetch_pendings['total_price'];
                        }
                     }
                  ?>
                  <h3>$<?php echo number_format($total_pendings, 2); ?></h3>
                  <p>Pending Payments</p>
               </div>
            </div>

            <!-- Completed Payments -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-check-circle"></i>
               </div>
               <div class="stat-info">
                  <?php
                     $total_completed = 0;
                     $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
                     if (mysqli_num_rows($select_completed) > 0) {
                        while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                           $total_completed += $fetch_completed['total_price'];
                        }
                     }
                  ?>
                  <h3>$<?php echo number_format($total_completed, 2); ?></h3>
                  <p>Completed Payments</p>
               </div>
            </div>

            <!-- Orders Placed -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-shopping-bag"></i>
               </div>
               <div class="stat-info">
                  <?php 
                     $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                     $number_of_orders = mysqli_num_rows($select_orders);
                  ?>
                  <h3><?php echo $number_of_orders; ?></h3>
                  <p>Orders Placed</p>
               </div>
            </div>

            <!-- Products -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-box-open"></i>
               </div>
               <div class="stat-info">
                  <?php 
                     $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                     $number_of_products = mysqli_num_rows($select_products);
                  ?>
                  <h3><?php echo $number_of_products; ?></h3>
                  <p>Products</p>
               </div>
            </div>

            <!-- Users -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-users"></i>
               </div>
               <div class="stat-info">
                  <?php 
                     $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                     $number_of_users = mysqli_num_rows($select_users);
                  ?>
                  <h3><?php echo $number_of_users; ?></h3>
                  <p>Customers</p>
               </div>
            </div>

            <!-- Admins -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-user-shield"></i>
               </div>
               <div class="stat-info">
                  <?php 
                     $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                     $number_of_admins = mysqli_num_rows($select_admins);
                  ?>
                  <h3><?php echo $number_of_admins; ?></h3>
                  <p>Admins</p>
               </div>
            </div>

            <!-- Accounts -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-user-circle"></i>
               </div>
               <div class="stat-info">
                  <?php 
                     $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                     $number_of_account = mysqli_num_rows($select_account);
                  ?>
                  <h3><?php echo $number_of_account; ?></h3>
                  <p>Total Accounts</p>
               </div>
            </div>

            <!-- Messages -->
            <div class="stat-card">
               <div class="stat-icon">
                  <i class="fas fa-envelope"></i>
               </div>
               <div class="stat-info">
                  <?php 
                     $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                     $number_of_messages = mysqli_num_rows($select_messages);
                  ?>
                  <h3><?php echo $number_of_messages; ?></h3>
                  <p>New Messages</p>
               </div>
            </div>
         </div>

         <!-- Recent Activity Section -->
         <div class="recent-activity">
            <h2>Recent Activity</h2>
            <div class="activity-grid">
               <?php
                  // Recent Orders
                  $recent_orders = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY placed_on DESC LIMIT 1") or die('query failed');
                  if (mysqli_num_rows($recent_orders) > 0) {
                     $fetch_order = mysqli_fetch_assoc($recent_orders);
               ?>
                  <div class="activity-card">
                     <i class="fas fa-shopping-bag"></i>
                     <p>New order from <?php echo htmlspecialchars($fetch_order['name']); ?></p>
                     <span>Placed on: <?php echo $fetch_order['placed_on']; ?></span>
                  </div>
               <?php } ?>

               <?php
                  // Recent Messages
                  $recent_messages = mysqli_query($conn, "SELECT * FROM `message` ORDER BY id DESC LIMIT 1") or die('query failed');
                  if (mysqli_num_rows($recent_messages) > 0) {
                     $fetch_message = mysqli_fetch_assoc($recent_messages);
               ?>
                  <div class="activity-card">
                     <i class="fas fa-envelope"></i>
                     <p>Message from <?php echo htmlspecialchars($fetch_message['name']); ?></p>
                     <span><?php echo htmlspecialchars(substr($fetch_message['message'], 0, 20)) . '...'; ?></span>
                  </div>
               <?php } ?>

               <?php
                  // Recent Users
                  $recent_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user' ORDER BY id DESC LIMIT 1") or die('query failed');
                  if (mysqli_num_rows($recent_users) > 0) {
                     $fetch_user = mysqli_fetch_assoc($recent_users);
               ?>
                  <div class="activity-card">
                     <i class="fas fa-user-plus"></i>
                     <p>New user: <?php echo htmlspecialchars($fetch_user['name']); ?></p>
                     <span>Email: <?php echo htmlspecialchars($fetch_user['email']); ?></span>
                  </div>
               <?php } ?>
            </div>
         </div>
      </section>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Add animation delay to stat cards for staggered effect
         document.querySelectorAll('.stat-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
         });

         // Add animation delay to activity cards
         document.querySelectorAll('.activity-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
         });
      });
   </script>
</body>
</html>