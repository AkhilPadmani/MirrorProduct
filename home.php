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
   <title>Arteneer | Premium Mirrors for Modern Spaces</title>
   <meta name="description" content="Discover handcrafted, minimalist mirrors designed to elevate your space. Arteneer offers premium quality with timeless designs.">
   
   <!-- Favicon -->
   <link rel="icon" href="images/favicon.ico" type="image/x-icon">

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

      /* Video Intro Overlay */
      .video-intro-overlay {
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100vh;
         background: rgba(0, 0, 0, 0.9);
         z-index: 9999;
         display: flex;
         justify-content: center;
         align-items: center;
         transition: opacity 1s ease;
      }

      .intro-video {
         width: 100%;
         height: 100%;
         object-fit: cover;
      }

      .skip-intro {
         position: absolute;
         bottom: 2rem;
         right: 2rem;
         background: var(--accent-color);
         color: var(--white);
         padding: 0.75rem 1.5rem;
         border-radius: 9999px;
         font-size: 1rem;
         font-weight: 500;
         cursor: pointer;
         transition: var(--transition);
      }

      .skip-intro:hover {
         background: #d97706;
         transform: scale(1.05);
      }

      /* Main Content */
      .main-content {
         opacity: 0;
         transition: opacity 0.5s ease;
      }

      /* Hero Section */
      .hero {
         position: relative;
         height: 100vh;
         min-height: 700px;
         display: flex;
         align-items: center;
         justify-content: center;
         text-align: center;
         color: var(--white);
         background: linear-gradient(to bottom, rgba(31, 41, 55, 0.8), rgba(31, 41, 55, 0.6));
         overflow: hidden;
      }

      .hero-video {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         object-fit: cover;
         z-index: -1;
         filter: brightness(0.7);
      }

      .hero-content {
         max-width: 900px;
         padding: 2rem;
         animation: fadeInUp 1s ease-out;
      }

      .hero h1 {
         font-family: var(--font-heading);
         font-size: 4rem;
         font-weight: 700;
         line-height: 1.1;
         margin-bottom: 1.5rem;
         text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
      }

      .hero p {
         font-size: 1.5rem;
         margin-bottom: 2.5rem;
         font-weight: 300;
      }

      .btn {
         padding: 1rem 2.5rem;
         background: var(--accent-color);
         color: var(--white);
         font-size: 1.125rem;
         font-weight: 600;
         border-radius: 7px;
         transition: var(--transition);
      }

      .btn:hover {
         background: #d97706;
         transform: translateY(-3px);
         box-shadow: var(--shadow);
      }

      .btn-outline {
         background: transparent;
         border: 2px solid var(--white);
         margin-left: 1rem;
         color: var(--white);
      }

      .btn-outline:hover {
         background: var(--white);
         color: var(--primary-color);
      }

      /* Featured Collection */
      .featured-collection {
         padding: 6rem 0;
         background: var(--white);
         position: relative;
         overflow: hidden;
      }

      .section-header {
         text-align: center;
         margin-bottom: 4rem;
         animation: fadeIn 1s ease-out;
      }

      .section-header h2 {
         font-family: var(--font-heading);
         font-size: 3rem;
         font-weight: 700;
         color: var(--primary-color);
         margin-bottom: 1rem;
         position: relative;
      }

      .section-header h2::after {
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

      .section-header p {
         font-size: 1.25rem;
         color: var(--secondary-color);
         max-width: 700px;
         margin: 0 auto;
      }

      .collection-tabs {
         display: flex;
         justify-content: center;
         gap: 1.5rem;
         margin-bottom: 3rem;
         flex-wrap: wrap;
      }

      .tab-btn {
         padding: 0.75rem 2rem;
         background: var(--light-bg);
         color: var(--primary-color);
         font-size: 1rem;
         font-weight: 600;
         border-radius: 9999px;
         border: 1px solid var(--gray);
         cursor: pointer;
         transition: var(--transition);
      }

      .tab-btn:hover {
         background: var(--accent-color);
         color: var(--white);
         border-color: var(--accent-color);
      }

      .tab-btn.active {
         background: var(--primary-color);
         color: var(--white);
         border-color: var(--primary-color);
      }

      /* Enhanced Product Card Animation */
      .product-card {
         background: linear-gradient(145deg, var(--white), #f0f0f0);
         border-radius: 1.5rem;
         overflow: hidden;
         box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 0 20px rgba(245, 158, 11, 0.3);
         transition: all 0.4s ease;
         transform-style: preserve-3d;
         animation: slideIn 0.6s ease-out forwards;
         position: relative;
         cursor: pointer;
      }

      .product-card:hover {
         transform: translateY(-15px) rotateX(5deg) rotateY(5deg);
         box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 0 30px rgba(245, 158, 11, 0.5);
         background: linear-gradient(145deg, #ffffff, #f8f8f8);
      }

      .product-image {
         height: 320px;
         overflow: hidden;
         position: relative;
         transition: all 0.4s ease;
      }

      .product-image img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         transition: transform 0.5s ease, filter 0.3s ease;
      }

      .product-card:hover .product-image img {
         transform: scale(1.15);
         filter: brightness(110%) contrast(110%);
      }

      .product-badge {
         position: absolute;
         top: 1rem;
         right: 1rem;
         background: linear-gradient(45deg, #ef4444, #f87171);
         color: var(--white);
         padding: 0.5rem 1rem;
         border-radius: 9999px;
         font-size: 0.875rem;
         font-weight: 600;
         z-index: 2;
         box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
         animation: pulse 2s infinite;
      }

      @keyframes pulse {
         0% { transform: scale(1); }
         50% { transform: scale(1.05); }
         100% { transform: scale(1); }
      }

      .product-info {
         padding: 1.5rem;
         text-align: center;
         background: var(--white);
         border-top: 1px solid rgba(245, 158, 11, 0.1);
         transition: all 0.3s ease;
      }

      .product-info h3 {
         font-size: 1.5rem;
         font-weight: 600;
         color: var(--primary-color);
         margin-bottom: 0.75rem;
         transition: color 0.3s ease;
      }

      .product-card:hover .product-info h3 {
         color: var(--accent-color);
      }

      .product-price {
         font-size: 1.75rem;
         font-weight: 700;
         color: var(--accent-color);
         margin-bottom: 1rem;
         transition: color 0.3s ease;
      }

      .product-card:hover .product-price {
         color: #d97706;
      }

      .qty, .btn {
         transition: all 0.3s ease;
      }

      .qty:focus, .btn:hover {
         box-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
         transform: scale(1.05);
      }

      /* Floating Labels for Input */
      .qty {
         position: relative;
         appearance: none;
         -webkit-appearance: none;
         -moz-appearance: none;
         width: 80px;
         padding: 0.75rem;
         margin-bottom: 1rem;
         border: 1px solid var(--gray);
         border-radius: 0.5rem;
         text-align: center;
         font-size: 1rem;
      }

      .qty:focus + label, .qty:not(:placeholder-shown) + label {
         transform: translateY(-20px) scale(0.8);
         color: var(--accent-color);
         opacity: 1;
      }

      .qty + label {
         position: absolute;
         top: 50%;
         left: 10px;
         transform: translateY(-50%);
         color: #6b7280;
         pointer-events: none;
         transition: all 0.3s ease;
         opacity: 0.7;
         font-size: 0.875rem;
      }

      .products-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
         gap: 2.5rem;
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 1rem;
         animation: fadeInGrid 1s ease-out;
      }

      @keyframes fadeInGrid {
         from {
            opacity: 0;
            transform: translateY(50px);
         }
         to {
            opacity: 1;
            transform: translateY(0);
         }
      }

      /* About Section */
      .about-section {
         padding: 6rem 0;
         background: var(--light-bg);
         position: relative;
      }

      .about-container {
         display: flex;
         align-items: center;
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 1rem;
         gap: 3rem;
         animation: fadeIn 1s ease-out;
      }

      .about-image {
         flex: 1;
         min-height: 450px;
         background-size: cover;
         background-position: center;
         border-radius: 1.5rem;
         box-shadow: var(--shadow);
         transition: transform 0.5s ease;
      }

      .about-image:hover {
         transform: scale(1.02);
      }

      .about-content {
         flex: 1;
      }

      .about-content h2 {
         font-family: var(--font-heading);
         font-size: 2.5rem;
         font-weight: 700;
         color: var(--primary-color);
         margin-bottom: 1.5rem;
      }

      .about-content p {
         font-size: 1.125rem;
         color: var(--secondary-color);
         margin-bottom: 2rem;
         line-height: 1.8;
      }

      /* Features Section */
      .features-section {
         padding: 6rem 0;
         background: var(--white);
      }

      .features-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         gap: 3rem;
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 1rem;
      }

      .feature-item {
         text-align: center;
         padding: 2.5rem;
         background: var(--light-bg);
         border-radius: 1.5rem;
         transition: var(--transition);
         animation: slideIn 0.5s ease-out;
         box-shadow: var(--shadow);
      }

      .feature-item:hover {
         transform: translateY(-10px);
         box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
         background: #fff7ed;
      }

      .feature-icon {
         font-size: 3rem;
         color: var(--accent-color);
         margin-bottom: 1.5rem;
         transition: transform 0.3s ease;
      }

      .feature-item:hover .feature-icon {
         transform: scale(1.2);
      }

      .feature-item h3 {
         font-size: 1.5rem;
         font-weight: 600;
         color: var(--primary-color);
         margin-bottom: 1rem;
      }

      .feature-item p {
         font-size: 1rem;
         color: var(--secondary-color);
      }

      /* Newsletter Section */
      .newsletter-section {
         padding: 5rem 0;
         background: var(--primary-color);
         color: var(--white);
         text-align: center;
         position: relative;
         overflow: hidden;
      }

      .newsletter-container {
         max-width: 700px;
         margin: 0 auto;
         padding: 0 1rem;
         animation: fadeIn 1s ease-out;
      }

      .newsletter-container h2 {
         font-family: var(--font-heading);
         font-size: 2.5rem;
         font-weight: 700;
         margin-bottom: 1rem;
      }

      .newsletter-container p {
         font-size: 1.125rem;
         margin-bottom: 2.5rem;
         opacity: 0.9;
      }

      .newsletter-form {
         display: flex;
         max-width: 600px;
         margin: 0 auto;
         gap: 0.75rem;
         background: var(--white);
         padding: 0.5rem;
         border-radius: 9999px;
         box-shadow: var(--shadow);
      }

      .newsletter-form input {
         flex: 1;
         padding: 1rem 1.5rem;
         font-size: 1rem;
         border: none;
         border-radius: 9999px;
         outline: none;
         background: transparent;
      }

      .newsletter-form button {
         padding: 1rem 2rem;
         background: var(--accent-color);
         color: var(--white);
         border: none;
         border-radius: 9999px;
         font-size: 1rem;
         font-weight: 600;
         cursor: pointer;
         transition: var(--transition);
      }

      .newsletter-form button:hover {
         background: #d97706;
         transform: scale(1.05);
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

      @keyframes slideIn {
         from { opacity: 0; transform: translateY(30px); }
         to { opacity: 1; transform: translateY(0); }
      }

      /* Responsive Adjustments */
      @media (max-width: 1024px) {
         .hero h1 { font-size: 3rem; }
         .hero p { font-size: 1.25rem; }
         .about-container { flex-direction: column; }
         .about-image { width: 100%; min-height: 350px; margin-bottom: 2rem; }
      }

      @media (max-width: 768px) {
         .hero h1 { font-size: 2.5rem; }
         .section-header h2 { font-size: 2.25rem; }
         .products-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }
         .collection-tabs { gap: 1rem; }
         .tab-btn { padding: 0.5rem 1.25rem; font-size: 0.875rem; }
      }

      @media (max-width: 576px) {
         .hero h1 { font-size: 2rem; }
         .hero p { font-size: 1rem; }
         .newsletter-form { flex-direction: column; }
         .newsletter-form input, .newsletter-form button { width: 100%; padding: 0.75rem; }
         .newsletter-form button { margin-top: 0.5rem; }
      }
   </style>
</head>
<body>
   
   <!-- Video Intro Overlay -->
   <div class="video-intro-overlay" id="videoIntro">
      <video autoplay muted class="intro-video" id="introVideo">
         <source src="videos/Arteneer.mp4" type="video/mp4">
         Your browser does not support the video tag.
      </video>
      <button class="skip-intro" id="skipIntro">Skip Intro</button>
   </div>

   <!-- Main Content -->
   <div class="main-content" id="mainContent">
      <?php include 'header.php'; ?>

      <?php
      if (isset($message)) {
         foreach ($message as $msg) {
            echo '<div class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg z-50 animate__animated animate__fadeInDown">' .
                 htmlspecialchars($msg) . ' <button onclick="this.parentElement.style.display=\'none\'" class="float-right text-red-500 hover:text-red-700">×</button></div>';
         }
      }
      ?>

      <!-- Hero Section -->
      <section class="hero">
         <video autoplay muted loop class="hero-video">
            <source src="videos/Arteneer.mp4" type="video/mp4">
            Your browser does not support the video tag.
         </video>

         <div class="hero-content">
            <h1>Elevate Your Space with Arteneer</h1>
            <p>Discover handcrafted mirrors that blend minimalist design with timeless elegance.</p>
            <div class="space-x-4">
               <a href="shop.php" class="btn">Explore Now</a>
               <a href="about.php" class="btn btn-outline">Our Craft</a>
            </div>
         </div>
      </section>

      <!-- Featured Collection -->
      <section class="featured-collection">
         <div class="section-header">
            <h2>Curated Mirrors</h2>
            <p>Explore our latest collections crafted to inspire and transform your interiors.</p>
         </div>

         <div class="collection-tabs">
            <button class="tab-btn active" data-category="all">All</button>
            <button class="tab-btn" data-category="new">New Arrivals</button>
            <button class="tab-btn" data-category="sale">On Sale</button>
            <button class="tab-btn" data-category="trending">Trending</button>
         </div>

         <div class="products-grid">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 8") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                  $categories = isset($fetch_products['categories']) ? $fetch_products['categories'] : 'all';
                  $is_sale = isset($fetch_products['sale_price']) && $fetch_products['sale_price'] < $fetch_products['price'];
                  $is_new = isset($fetch_products['is_new']) && $fetch_products['is_new'];
            ?>
            <form action="" method="post" class="product-card" data-categories="<?php echo $categories; ?>">
               <?php if ($is_new): ?>
                  <div class="product-badge">New</div>
               <?php elseif ($is_sale): ?>
                  <div class="product-badge sale">Sale</div>
               <?php endif; ?>
               <div class="product-image">
                  <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
               </div>
               <div class="product-info">
                  <h3><?php echo $fetch_products['name']; ?></h3>
                  <div class="product-price">
                     <?php if ($is_sale): ?>
                        <span>$<?php echo $fetch_products['sale_price']; ?></span>
                        <span class="original-price">$<?php echo $fetch_products['price']; ?></span>
                     <?php else: ?>
                        $<?php echo $fetch_products['price']; ?>
                     <?php endif; ?>
                  </div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty" id="qty_<?php echo $fetch_products['id']; ?>" placeholder="Quantity">
                  <label for="qty_<?php echo $fetch_products['id']; ?>">Quantity</label>
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $is_sale ? $fetch_products['sale_price'] : $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <input type="submit" value="Add to Cart" name="add_to_cart" class="btn w-full mt-4">
               </div>
            </form>
            <?php
               }
            } else {
               echo '<p class="text-center text-gray-600 text-lg col-span-full">No products available yet!</p>';
            }
            ?>
         </div>

         <div class="text-center mt-12">
            <a href="shop.php" class="btn btn-outline px-8 py-3">Discover More</a>
         </div>
      </section>

      <!-- About Section -->
      <section class="about-section">
         <div class="about-container">
            <div class="about-image" style="background-image: url('images/about-img.jpg');"></div>
            <div class="about-content">
               <h2>Timeless Craftsmanship</h2>
               <p>At Arteneer, we fuse artistry with functionality, crafting mirrors that redefine elegance. Our artisans use sustainable materials to create designs that stand the test of time.</p>
               <p>Join us in celebrating beauty that’s both purposeful and enduring.</p>
               <a href="about.php" class="btn">Explore Our Story</a>
            </div>
         </div>
      </section>

      <!-- Features Section -->
      <section class="features-section">
         <div class="section-header">
            <h2>Why Arteneer?</h2>
            <p>Unmatched quality and design for the modern home.</p>
         </div>
         <div class="features-grid">
            <div class="feature-item">
               <div class="feature-icon"><i class="fas fa-award"></i></div>
               <h3>Superior Quality</h3>
               <p>Precision-crafted with premium materials for lasting brilliance.</p>
            </div>
            <div class="feature-item">
               <div class="feature-icon"><i class="fas fa-paint-brush"></i></div>
               <h3>Elegant Design</h3>
               <p>Minimalist aesthetics that elevate any space effortlessly.</p>
            </div>
            <div class="feature-item">
               <div class="feature-icon"><i class="fas fa-truck"></i></div>
               <h3>Secure Delivery</h3>
               <p>Expertly packaged to ensure safe arrival at your doorstep.</p>
            </div>
            <div class="feature-item">
               <div class="feature-icon"><i class="fas fa-headset"></i></div>
               <h3>Personalized Support</h3>
               <p>Our team is here to guide you every step of the way.</p>
            </div>
         </div>
      </section>

      <!-- Newsletter Section -->
      <section class="newsletter-section">
         <div class="newsletter-container">
            <h2>Stay Inspired</h2>
            <p>Get exclusive updates on new arrivals, promotions, and design tips.</p>
            <form action="" method="post" class="newsletter-form">
               <input type="email" name="email" placeholder="Enter your email" required>
               <button type="submit" name="subscribe">Join Now</button>
            </form>
         </div>
      </section>

      <?php include 'footer.php'; ?>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Video Intro
         const videoIntro = document.getElementById('videoIntro');
         const introVideo = document.getElementById('introVideo');
         const skipIntroBtn = document.getElementById('skipIntro');
         const mainContent = document.getElementById('mainContent');
         const hasSeenIntro = localStorage.getItem('hasSeenIntro');

         if (hasSeenIntro) {
            videoIntro.style.display = 'none';
            mainContent.style.opacity = '1';
         } else {
            localStorage.setItem('hasSeenIntro', 'true');
            introVideo.onended = function() {
               videoIntro.style.opacity = '0';
               setTimeout(() => {
                  videoIntro.style.display = 'none';
                  mainContent.style.opacity = '1';
               }, 1000);
            };
            skipIntroBtn.addEventListener('click', function() {
               introVideo.pause();
               videoIntro.style.opacity = '0';
               setTimeout(() => {
                  videoIntro.style.display = 'none';
                  mainContent.style.opacity = '1';
               }, 1000);
            });
         }

         // Collection Tabs
         const tabButtons = document.querySelectorAll('.tab-btn');
         const productCards = document.querySelectorAll('.product-card');
         tabButtons.forEach(button => {
            button.addEventListener('click', () => {
               tabButtons.forEach(btn => btn.classList.remove('active'));
               button.classList.add('active');
               const category = button.dataset.category;
               productCards.forEach(card => {
                  const categories = card.dataset.categories.split(' ');
                  card.style.display = (category === 'all' || categories.includes(category)) ? 'block' : 'none';
               });
            });
         });

         // Product Card Hover Effects
         productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
               this.style.transform = 'translateY(-15px) rotateX(5deg) rotateY(5deg)';
               this.querySelector('.product-image img').style.transform = 'scale(1.15)';
               this.querySelector('.product-image img').style.filter = 'brightness(110%) contrast(110%)';
            });

            card.addEventListener('mouseleave', function() {
               this.style.transform = 'translateY(0) rotateX(0) rotateY(0)';
               this.querySelector('.product-image img').style.transform = 'scale(1)';
               this.querySelector('.product-image img').style.filter = 'brightness(100%) contrast(100%)';
            });

            // Add click animation for buttons
            const buttons = card.querySelectorAll('.btn');
            buttons.forEach(btn => {
               btn.addEventListener('click', function(e) {
                  e.preventDefault();
                  this.style.transform = 'scale(0.95)';
                  setTimeout(() => {
                     this.style.transform = 'scale(1)';
                     this.form.submit(); // Submit the form after animation
                  }, 100);
               });
            });
         });

         // Smooth Scroll for Links
         document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
               e.preventDefault();
               document.querySelector(this.getAttribute('href')).scrollIntoView({
                  behavior: 'smooth'
               });
            });
         });
      });
   </script>
</body>
</html>