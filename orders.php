<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Handle order cancellation
if (isset($_POST['cancel_order'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $check_status = mysqli_query($conn, "SELECT status FROM `orders` WHERE id = '$order_id' AND user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($check_status) > 0) {
        $order = mysqli_fetch_assoc($check_status);
        if ($order['status'] === 'Pending') {
            mysqli_query($conn, "UPDATE `orders` SET status = 'Cancelled' WHERE id = '$order_id' AND user_id = '$user_id'") or die('query failed');
            $message[] = 'Order cancelled successfully!';
        } else {
            $message[] = 'Cannot cancel order after dispatch!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Arteneer | Your Orders</title>
   <meta name="description" content="View your order history with Arteneer, featuring premium mirrors for modern spaces.">

   <!-- Tailwind CSS CDN -->
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <!-- Animate.css CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

   <style>
      :root {
         --primary-color: #1f2937; /* Dark slate */
         --secondary-color: #4b5563; /* Gray */
         --accent-color: #f59e0b; /* Amber */
         --light-bg: #f9fafb; /* Light gray */
         --white: #ffffff;
         --shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
         --transition: all 0.3s ease;
         --font-main: 'Poppins', sans-serif;
         --font-heading: 'Playfair Display', serif;
      }

      body {
         font-family: var(--font-main);
         background-color: var(--light-bg);
         color: var(--primary-color);
         line-height: 1.6;
         overflow-x: hidden;
      }

      /* Heading Section */
      .heading {
         background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
         color: var(--white);
         text-align: center;
         padding: 4rem 2rem;
         position: relative;
         overflow: hidden;
         animation: fadeIn 1s ease-out;
      }

      .heading h3 {
         font-family: var(--font-heading);
         font-size: 3rem;
         font-weight: 700;
         text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      }

      .heading p {
         font-size: 1.125rem;
         margin-top: 0.5rem;
      }

      .heading p a {
         color: var(--accent-color);
         font-weight: 500;
         transition: var(--transition);
      }

      .heading p a:hover {
         text-decoration: underline;
      }

      /* Orders Section */
      .placed-orders {
         padding: 6rem 0;
         background: var(--white);
      }

      .placed-orders .title {
         text-align: center;
         font-family: var(--font-heading);
         font-size: 2.5rem;
         font-weight: 700;
         color: var(--primary-color);
         margin-bottom: 4rem;
         position: relative;
      }

      .placed-orders .title::after {
         content: '';
         position: absolute;
         bottom: -0.5rem;
         left: 50%;
         transform: translateX(-50%);
         width: 80px;
         height: 3px;
         background: var(--accent-color);
         border-radius: 2px;
      }

      .placed-orders .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
         gap: 2rem;
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 2rem;
      }

      .placed-orders .box {
         background: var(--light-bg);
         border-radius: 1.5rem;
         padding: 2rem;
         box-shadow: var(--shadow);
         transition: var(--transition);
         animation: slideIn 0.5s ease-out;
         border: 1px solid rgba(0, 0, 0, 0.05);
      }

      .placed-orders .box:hover {
         transform: translateY(-10px);
         box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
         background: #fff7ed;
      }

      .placed-orders .box p {
         font-size: 1rem;
         color: var(--secondary-color);
         margin-bottom: 1rem;
         display: flex;
         justify-content: space-between;
         align-items: center;
      }

      .placed-orders .box p span {
         font-weight: 500;
         color: var(--primary-color);
         transition: color 0.3s ease;
      }

      .placed-orders .box:hover p span {
         color: var(--accent-color);
      }

      .placed-orders .box p span[style*="color:red"] {
         color: #ef4444;
         font-weight: 600;
      }

      .placed-orders .box p span[style*="color:green"] {
         color: #10b981;
         font-weight: 600;
      }

      .placed-orders .box .tracking-info {
         margin-top: 1rem;
         padding-top: 1rem;
         border-top: 1px solid var(--secondary-color);
         font-size: 0.9rem;
         color: var(--secondary-color);
      }

      .placed-orders .box .tracking-info span {
         color: var(--primary-color);
         font-weight: 500;
      }

      .placed-orders .box .btn {
         display: inline-block;
         padding: 0.75rem 1.5rem;
         background: #ef4444;
         color: var(--white);
         font-size: 1rem;
         font-weight: 600;
         border-radius: 0.5rem;
         transition: var(--transition);
         margin-top: 1rem;
         cursor: pointer;
      }

      .placed-orders .box .btn:hover {
         background: #dc2626;
         transform: scale(1.05);
      }

      .placed-orders .empty {
         text-align: center;
         font-size: 1.5rem;
         color: var(--secondary-color);
         padding: 4rem 0;
         animation: fadeIn 1s ease-out;
      }

      /* Animations */
      @keyframes fadeIn {
         from { opacity: 0; }
         to { opacity: 1; }
      }

      @keyframes slideIn {
         from { opacity: 0; transform: translateY(30px); }
         to { opacity: 1; transform: translateY(0); }
      }

      /* Responsive Adjustments */
      @media (max-width: 1024px) {
         .placed-orders .box-container { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
      }

      @media (max-width: 768px) {
         .heading h3 { font-size: 2.5rem; }
         .placed-orders .title { font-size: 2rem; }
         .placed-orders .box { padding: 1.5rem; }
         .placed-orders .box p { font-size: 0.875rem; flex-direction: column; align-items: flex-start; gap: 0.5rem; }
      }

      @media (max-width: 576px) {
         .heading h3 { font-size: 2rem; }
         .placed-orders .title { font-size: 1.75rem; }
         .placed-orders .box-container { grid-template-columns: 1fr; }
      }
   </style>
</head>
<body>
   
   <?php include 'header.php'; ?>

   <!-- Heading Section -->
   <div class="heading">
      <h3>Your Orders</h3>
      <p><a href="home.php">Home</a> / Orders</p>
   </div>

   <!-- Orders Section -->
   <section class="placed-orders">
      <h1 class="title">Placed Orders</h1>
      <div class="box-container">
         <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
         ?>
         <div class="box">
            <p>Order ID: <span><?php echo $fetch_orders['id']; ?></span></p>
            <p>Placed On: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
            <p>Name: <span><?php echo $fetch_orders['name']; ?></span></p>
            <p>Number: <span><?php echo $fetch_orders['number']; ?></span></p>
            <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
            <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
            <p>Payment Method: <span><?php echo $fetch_orders['method']; ?></span></p>
            <p>Your Orders: <span><?php echo $fetch_orders['total_products']; ?></span></p>
            <p>Total Price: <span>$<?php echo $fetch_orders['total_price']; ?>/-</span></p>
            <p>Payment Status: <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? '#ef4444' : '#10b981'; ?>;"><?php echo $fetch_orders['payment_status']; ?></span></p>
            <p>Order Status: <span style="color:<?php echo ($fetch_orders['status'] == 'Pending') ? '#ef4444' : ($fetch_orders['status'] == 'Cancelled' ? '#dc2626' : '#10b981'); ?>;"><?php echo $fetch_orders['status']; ?></span></p>
            <?php if ($fetch_orders['status'] !== 'Pending' && $fetch_orders['status'] !== 'Cancelled') { ?>
               <div class="tracking-info">
                  <p>Delivery Partner: <span><?php echo $fetch_orders['delivery_partner_name'] ?: 'Assigned Soon'; ?></span></p>
                  <p>Contact: <span><?php echo $fetch_orders['delivery_partner_contact'] ?: 'N/A'; ?></span></p>
                  <p>Tracking Status: <span><?php echo $fetch_orders['tracking_status'] ?: 'Preparing for Dispatch'; ?></span></p>
               </div>
            <?php } ?>
            <?php if ($fetch_orders['status'] === 'Pending') { ?>
               <form action="" method="post" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                  <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                  <input type="submit" name="cancel_order" value="Cancel Order" class="btn">
               </form>
            <?php } ?>
         </div>
         <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
         ?>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Animation delays for order boxes
         document.querySelectorAll('.placed-orders .box').forEach((box, index) => {
            box.style.animationDelay = `${index * 0.1}s`;
         });

         // Simulate real-time tracking (for demo purposes)
         function updateTracking() {
            document.querySelectorAll('.tracking-info').forEach(info => {
               const statusSpan = info.querySelector('p:last-child span');
               const currentStatus = statusSpan.textContent;
               const statuses = ['Preparing for Dispatch', 'Out for Delivery', 'Near Your Location', 'Delivered'];
               const currentIndex = statuses.indexOf(currentStatus);
               if (currentIndex < statuses.length - 1) {
                  statusSpan.textContent = statuses[currentIndex + 1];
               }
            });
         }

         // Simulate periodic updates (every 10 seconds for demo)
         setInterval(updateTracking, 10000);
      });
   </script>
</body>
</html>