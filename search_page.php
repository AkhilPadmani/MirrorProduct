<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Product added to cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Arteneer | Search Products</title>
   <meta name="description" content="Search for premium mirrors at Arteneer to find the perfect piece for your space.">

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

      /* Search Form Section */
      .search-form {
         padding: 4rem 0;
         background: var(--white);
         text-align: center;
      }

      .search-form form {
         max-width: 700px;
         margin: 0 auto;
         padding: 0 2rem;
         display: flex;
         gap: 1rem;
         animation: fadeInUp 1s ease-out;
      }

      .search-form input[type="text"] {
         flex: 1;
         padding: 1rem 1.5rem;
         border: 1px solid var(--secondary-color);
         border-radius: 9999px;
         font-size: 1rem;
         color: var(--primary-color);
         background: var(--light-bg);
         transition: var(--transition);
      }

      .search-form input[type="text"]:focus {
         border-color: var(--accent-color);
         box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
         outline: none;
      }

      .search-form input[type="submit"] {
         padding: 1rem 2.5rem;
         background: var(--accent-color);
         color: var(--white);
         font-size: 1rem;
         font-weight: 600;
         border-radius: 9999px;
         border: none;
         cursor: pointer;
         transition: var(--transition);
      }

      .search-form input[type="submit"]:hover {
         background: #d97706;
         transform: scale(1.05);
         box-shadow: var(--shadow);
      }

      /* Products Section */
      .products {
         padding: 0 0 6rem;
         background: var(--white);
      }

      .products .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
         gap: 2rem;
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 2rem;
      }

      .products .box {
         background: var(--light-bg);
         border-radius: 1.5rem;
         overflow: hidden;
         box-shadow: var(--shadow);
         transition: var(--transition);
         animation: slideIn 0.5s ease-out;
         position: relative;
         border: 1px solid rgba(0, 0, 0, 0.05);
      }

      .products .box:hover {
         transform: translateY(-10px);
         box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
      }

      .products .image {
         height: 360px;
         overflow: hidden;
         position: relative;
      }

      .products .image img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         transition: transform 0.5s ease;
      }

      .products .box:hover .image img {
         transform: scale(1.1);
      }

      .products .box .name {
         font-size: 1.5rem;
         font-weight: 600;
         color: var(--primary-color);
         text-align: center;
         margin: 1rem 0;
         transition: color 0.3s ease;
      }

      .products .box:hover .name {
         color: var(--accent-color);
      }

      .products .box .price {
         font-size: 1.75rem;
         font-weight: 700;
         color: var(--accent-color);
         text-align: center;
         margin-bottom: 1rem;
      }

      .products .box .qty {
         width: 80px;
         padding: 0.75rem;
         margin: 0 auto 1rem;
         border: 1px solid var(--secondary-color);
         border-radius: 9999px;
         text-align: center;
         font-size: 1rem;
         background: var(--white);
         display: block;
         transition: border-color 0.3s ease;
      }

      .products .box .qty:focus {
         border-color: var(--accent-color);
         outline: none;
      }

      .products .box .btn {
         display: block;
         width: 100%;
         padding: 0.75rem;
         background: var(--accent-color);
         color: var(--white);
         font-size: 1rem;
         font-weight: 600;
         border-radius: 9999px;
         text-align: center;
         transition: var(--transition);
         cursor: pointer;
         margin: 0 1rem 1rem;
      }

      .products .box .btn:hover {
         background: #d97706;
         transform: scale(1.05);
         box-shadow: var(--shadow);
      }

      .products .empty {
         text-align: center;
         font-size: 1.5rem;
         color: var(--secondary-color);
         padding: 4rem 0;
         animation: fadeIn 1s ease-out;
      }

      /* Message Toast */
      .message {
         position: fixed;
         top: 20px;
         left: 50%;
         transform: translateX(-50%);
         background: var(--white);
         padding: 1rem 2rem;
         border-radius: 0.5rem;
         box-shadow: var(--shadow);
         display: flex;
         align-items: center;
         z-index: 10000;
         border-left: 4px solid var(--accent-color);
         animation: fadeInDown 0.5s ease-out;
      }

      .message span {
         margin-right: 1rem;
         font-size: 1rem;
         color: var(--primary-color);
      }

      .message i {
         color: var(--secondary-color);
         cursor: pointer;
         font-size: 1.25rem;
         transition: var(--transition);
      }

      .message i:hover {
         color: var(--accent-color);
         transform: scale(1.2);
      }

      /* Animations */
      @keyframes fadeIn {
         from { opacity: 0; }
         to { opacity: 1; }
      }

      @keyframes fadeInUp {
         from { opacity: 0; transform: translateY(30px); }
         to { opacity: 1; transform: translateY(0); }
      }

      @keyframes fadeInDown {
         from { opacity: 0; transform: translateY(-30px); }
         to { opacity: 1; transform: translateY(0); }
      }

      @keyframes slideIn {
         from { opacity: 0; transform: translateY(30px); }
         to { opacity: 1; transform: translateY(0); }
      }

      /* Responsive Adjustments */
      @media (max-width: 1024px) {
         .products .box-container { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
      }

      @media (max-width: 768px) {
         .heading h3 { font-size: 2.5rem; }
         .search-form form { flex-direction: column; padding: 0 1rem; }
         .search-form input[type="text"], .search-form input[type="submit"] { width: 100%; }
         .products .box .name { font-size: 1.25rem; }
         .products .box .price { font-size: 1.5rem; }
      }

      @media (max-width: 576px) {
         .heading h3 { font-size: 2rem; }
         .products .box-container { grid-template-columns: 1fr; }
         .products .image { height: 300px; }
         .products .box .btn { margin: 0 0.5rem 0.5rem; }
      }
   </style>
</head>
<body>
   
   <?php include 'header.php'; ?>

   <!-- Heading Section -->
   <div class="heading">
      <h3>Search Products</h3>
      <p><a href="home.php">Home</a> / Search</p>
   </div>

   <!-- Search Form Section -->
   <section class="search-form">
      <form action="" method="post">
         <input type="text" name="search" placeholder="Search products..." class="box">
         <input type="submit" name="submit" value="Search" class="btn">
      </form>
   </section>

   <!-- Products Section -->
   <section class="products">
      <div class="box-container">
         <?php
         if (isset($_POST['submit'])) {
            $search_item = mysqli_real_escape_string($conn, $_POST['search']);
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_product = mysqli_fetch_assoc($select_products)) {
         ?>
         <form action="" method="post" class="box">
            <div class="image">
               <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="<?php echo $fetch_product['name']; ?>">
            </div>
            <div class="name"><?php echo $fetch_product['name']; ?></div>
            <div class="price">$<?php echo $fetch_product['price']; ?></div>
            <input type="number" class="qty" name="product_quantity" min="1" value="1">
            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
            <input type="submit" class="btn" value="Add to Cart" name="add_to_cart">
         </form>
         <?php
               }
            } else {
               echo '<p class="empty">No results found!</p>';
            }
         } else {
            echo '<p class="empty">Search for something!</p>';
         }
         ?>
      </div>
   </section>

   <?php
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '<div class="message">' .
              '<span>' . htmlspecialchars($msg) . '</span>' .
              '<i class="fas fa-times" onclick="this.parentElement.remove();"></i>' .
              '</div>';
      }
   }
   ?>

   <?php include 'footer.php'; ?>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Animation delays for product boxes
         document.querySelectorAll('.products .box').forEach((box, index) => {
            box.style.animationDelay = `${index * 0.1}s`;
         });

         // Auto-remove messages after 5 seconds
         setTimeout(() => {
            document.querySelectorAll('.message').forEach(msg => {
               msg.style.transition = 'opacity 0.5s ease';
               msg.style.opacity = '0';
               setTimeout(() => msg.remove(), 500);
            });
         }, 5000);
      });
   </script>
</body>
</html>