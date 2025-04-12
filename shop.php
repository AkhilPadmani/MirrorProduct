<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Currency and Language settings
$default_currency = 'USD';
$default_language = 'en';

$currencies = [
    'USD' => ['symbol' => '$', 'rate' => 1.00, 'name' => 'US Dollar'],
    'EUR' => ['symbol' => '€', 'rate' => 0.85, 'name' => 'Euro'],
    'GBP' => ['symbol' => '£', 'rate' => 0.73, 'name' => 'British Pound'],
    'INR' => ['symbol' => '₹', 'rate' => 74.50, 'name' => 'Indian Rupee'],
    'JPY' => ['symbol' => '¥', 'rate' => 110.00, 'name' => 'Japanese Yen']
];

$languages = [
    'en' => 'English',
    'es' => 'Español',
    'fr' => 'Français',
    'de' => 'Deutsch',
    'ja' => '日本語'
];

$current_currency = isset($_SESSION['currency']) ? $_SESSION['currency'] : $default_currency;
$current_language = isset($_SESSION['language']) ? $_SESSION['language'] : $default_language;

if (isset($_POST['change_currency'])) {
    $_SESSION['currency'] = $_POST['currency'];
    $current_currency = $_SESSION['currency'];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['change_language'])) {
    $_SESSION['language'] = $_POST['language'];
    $current_language = $_SESSION['language'];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = ($current_language == 'es') ? '¡Ya añadido al carrito!' : 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = ($current_language == 'es') ? '¡Producto añadido al carrito!' : 'Product added to cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $current_language; ?>">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo ($current_language == 'es') ? 'Tienda | Arteneer' : 'Shop | Arteneer'; ?></title>

   <!-- Tailwind CSS CDN -->
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <!-- Animate.css CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

   <!-- Stripe.js for payment integration -->
   <script src="https://js.stripe.com/v3/"></script>

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

      /* Shop Section */
      .products {
         padding: 6rem 0;
         background: var(--white);
      }

      .shop-controls {
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 2rem;
         display: flex;
         justify-content: space-between;
         align-items: center;
         flex-wrap: wrap;
         gap: 2rem;
         margin-bottom: 3rem;
      }

      .shop-controls .title {
         font-family: var(--font-heading);
         font-size: 2.5rem;
         font-weight: 700;
         color: var(--primary-color);
      }

      .control-group {
         display: flex;
         align-items: center;
         gap: 1rem;
      }

      .control-label {
         font-size: 1rem;
         font-weight: 500;
         color: var(--secondary-color);
      }

      select {
         padding: 0.75rem 1.5rem;
         border: 1px solid var(--secondary-color);
         border-radius: 9999px;
         font-size: 1rem;
         background: var(--white);
         color: var(--primary-color);
         cursor: pointer;
         transition: var(--transition);
      }

      select:hover {
         border-color: var(--accent-color);
      }

      /* Filter Section */
      .filter-section {
         max-width: 1280px;
         margin: 0 auto;
         padding: 2rem;
         background: var(--light-bg);
         border-radius: 1.5rem;
         box-shadow: var(--shadow);
         margin-bottom: 4rem;
         animation: fadeInUp 1s ease-out;
      }

      .filter-title {
         font-family: var(--font-heading);
         font-size: 1.75rem;
         font-weight: 600;
         color: var(--primary-color);
         margin-bottom: 1.5rem;
      }

      .filter-options {
         display: flex;
         flex-wrap: wrap;
         gap: 1rem;
      }

      .filter-btn {
         padding: 0.75rem 1.5rem;
         background: var(--white);
         border: 1px solid var(--secondary-color);
         border-radius: 9999px;
         font-size: 0.875rem;
         font-weight: 500;
         color: var(--primary-color);
         cursor: pointer;
         transition: var(--transition);
      }

      .filter-btn:hover, .filter-btn.active {
         background: var(--accent-color);
         color: var(--white);
         border-color: var(--accent-color);
         transform: scale(1.05);
      }

      /* Products Grid */
      .products-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
         gap: 2rem;
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 2rem;
      }

      .product-card {
         background: var(--white);
         border-radius: 1.5rem;
         overflow: hidden;
         box-shadow: var(--shadow);
         transition: var(--transition), transform 0.5s ease;
         animation: slideIn 0.5s ease-out;
         position: relative;
         border: 1px solid rgba(0, 0, 0, 0.05);
      }

      .product-card:hover {
         transform: translateY(-10px) scale(1.02);
         box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      }

      .product-image {
         height: 360px;
         overflow: hidden;
         position: relative;
      }

      .product-image img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         transition: transform 0.5s ease;
      }

      .product-card:hover .product-image img {
         transform: scale(1.1);
      }

      .product-image .overlay {
         position: absolute;
         inset: 0;
         background: rgba(0, 0, 0, 0);
         transition: background 0.3s ease;
         display: flex;
         align-items: center;
         justify-content: center;
         opacity: 0;
      }

      .product-card:hover .overlay {
         background: rgba(0, 0, 0, 0.4);
         opacity: 1;
      }

      .product-info {
         padding: 1.5rem;
         text-align: center;
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
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 0.5rem;
      }

      .product-price .original-price {
         font-size: 1.125rem;
         color: var(--secondary-color);
         text-decoration: line-through;
      }

      .qty {
         width: 80px;
         padding: 0.75rem;
         margin-bottom: 1rem;
         border: 1px solid var(--secondary-color);
         border-radius: 9999px;
         text-align: center;
         font-size: 1rem;
         background: var(--light-bg);
         transition: border-color 0.3s ease;
      }

      .qty:focus {
         border-color: var(--accent-color);
         outline: none;
      }

      .btn {
         padding: 0.75rem 1.5rem;
         background: var(--accent-color);
         color: var(--white);
         font-size: 1rem;
         font-weight: 600;
         border-radius: 9999px;
         transition: var(--transition);
         width: 100%;
         cursor: pointer;
      }

      .btn:hover {
         background: #d97706;
         transform: scale(1.05);
         box-shadow: var(--shadow);
      }

      .btn-outline {
         background: transparent;
         border: 2px solid var(--accent-color);
         color: var(--accent-color);
         margin-top: 0.5rem;
      }

      .btn-outline:hover {
         background: var(--accent-color);
         color: var(--white);
      }

      .empty {
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
         .shop-controls { flex-direction: column; align-items: flex-start; }
         .products-grid { grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); }
      }

      @media (max-width: 768px) {
         .heading h3 { font-size: 2.5rem; }
         .shop-controls .title { font-size: 2rem; }
         .control-group { width: 100%; justify-content: space-between; }
         .filter-options { gap: 0.75rem; }
         .filter-btn { padding: 0.5rem 1rem; font-size: 0.875rem; }
      }

      @media (max-width: 576px) {
         .heading h3 { font-size: 2rem; }
         .products-grid { grid-template-columns: 1fr; }
         .product-image { height: 300px; }
         .product-info h3 { font-size: 1.25rem; }
         .product-price { font-size: 1.5rem; }
      }
   </style>
</head>
<body>
   
   <?php include 'header.php'; ?>

   <!-- Heading Section -->
   <div class="heading">
      <h3><?php echo ($current_language == 'es') ? 'Nuestra Tienda' : 'Our Shop'; ?></h3>
      <p><a href="home.php"><?php echo ($current_language == 'es') ? 'Inicio' : 'Home'; ?></a> / <?php echo ($current_language == 'es') ? 'Tienda' : 'Shop'; ?></p>
   </div>

   <!-- Shop Section -->
   <section class="products">
      <div class="shop-controls">
         <h1 class="title"><?php echo ($current_language == 'es') ? 'Últimos Productos' : 'Latest Products'; ?></h1>
         <div class="control-group">
            <span class="control-label"><?php echo ($current_language == 'es') ? 'Moneda:' : 'Currency:'; ?></span>
            <form method="post" class="currency-form">
               <select name="currency" onchange="this.form.submit()">
                  <?php foreach ($currencies as $code => $currency): ?>
                     <option value="<?php echo $code; ?>" <?php echo ($current_currency == $code) ? 'selected' : ''; ?>>
                        <?php echo $currency['name'] . ' (' . $currency['symbol'] . ')'; ?>
                     </option>
                  <?php endforeach; ?>
               </select>
               <input type="hidden" name="change_currency" value="1">
            </form>
         </div>
         <div class="control-group">
            <span class="control-label"><?php echo ($current_language == 'es') ? 'Idioma:' : 'Language:'; ?></span>
            <form method="post" class="language-form">
               <select name="language" onchange="this.form.submit()">
                  <?php foreach ($languages as $code => $name): ?>
                     <option value="<?php echo $code; ?>" <?php echo ($current_language == $code) ? 'selected' : ''; ?>>
                        <?php echo $name; ?>
                     </option>
                  <?php endforeach; ?>
               </select>
               <input type="hidden" name="change_language" value="1">
            </form>
         </div>
      </div>

      <div class="filter-section">
         <h3 class="filter-title"><?php echo ($current_language == 'es') ? 'Filtrar por:' : 'Filter by:'; ?></h3>
         <div class="filter-options">
            <button class="filter-btn active" data-filter="all"><?php echo ($current_language == 'es') ? 'Todos' : 'All'; ?></button>
            <button class="filter-btn" data-filter="wall"><?php echo ($current_language == 'es') ? 'Pared' : 'Wall'; ?></button>
            <button class="filter-btn" data-filter="floor"><?php echo ($current_language == 'es') ? 'Piso' : 'Floor'; ?></button>
            <button class="filter-btn" data-filter="bathroom"><?php echo ($current_language == 'es') ? 'Baño' : 'Bathroom'; ?></button>
            <button class="filter-btn" data-filter="decorative"><?php echo ($current_language == 'es') ? 'Decorativo' : 'Decorative'; ?></button>
         </div>
      </div>

      <div class="products-grid">
         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
               $original_price = $fetch_products['price'];
               $converted_price = $original_price * $currencies[$current_currency]['rate'];
               $formatted_price = $currencies[$current_currency]['symbol'] . number_format($converted_price, 2);
               $category = isset($fetch_products['category']) ? strtolower($fetch_products['category']) : 'all';
         ?>
         <form action="" method="post" class="product-card group" data-category="<?php echo $category; ?>">
            <div class="product-image">
               <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
               <div class="overlay">
                  <button type="submit" name="add_to_cart" class="btn"><?php echo ($current_language == 'es') ? 'Añadir al Carrito' : 'Add to Cart'; ?></button>
               </div>
            </div>
            <div class="product-info">
               <h3><?php echo $fetch_products['name']; ?></h3>
               <div class="product-price"><?php echo $formatted_price; ?></div>
               <input type="number" min="1" name="product_quantity" value="1" class="qty">
               <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $original_price; ?>">
               <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
               <!-- Uncomment and configure if using Stripe -->
               <!-- <button type="button" class="btn btn-outline quick-buy-btn" data-product-id="<?php echo $fetch_products['id']; ?>" data-product-name="<?php echo $fetch_products['name']; ?>" data-product-price="<?php echo $original_price; ?>" data-product-currency="<?php echo $current_currency; ?>">
                  <?php echo ($current_language == 'es') ? 'Comprar Ahora' : 'Buy Now'; ?>
               </button> -->
            </div>
         </form>
         <?php
            }
         } else {
            echo '<p class="empty">' . (($current_language == 'es') ? '¡No hay productos añadidos todavía!' : 'No products added yet!') . '</p>';
         }
         ?>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Filter products by category
         const filterButtons = document.querySelectorAll('.filter-btn');
         const productCards = document.querySelectorAll('.product-card');

         filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
               filterButtons.forEach(b => b.classList.remove('active'));
               btn.classList.add('active');
               const filter = btn.dataset.filter;

               productCards.forEach(card => {
                  const category = card.dataset.category;
                  card.style.display = (filter === 'all' || category.includes(filter)) ? 'block' : 'none';
               });
            });
         });

         // Product Card Animation Delay
         productCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
         });

         // Stripe Integration (Uncomment and configure if needed)
         /*
         const stripe = Stripe('pk_test_your_publishable_key_here');
         document.querySelectorAll('.quick-buy-btn').forEach(button => {
            button.addEventListener('click', async () => {
               const productId = button.dataset.productId;
               const productName = button.dataset.productName;
               const productPrice = button.dataset.productPrice;
               const currency = button.dataset.productCurrency;
               button.innerHTML = '<?php echo ($current_language == 'es') ? "Procesando..." : "Processing..."; ?> <i class="fas fa-spinner fa-spin"></i>';
               button.disabled = true;

               try {
                  const response = await fetch('create_checkout_session.php', {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json' },
                     body: JSON.stringify({
                        productId: productId,
                        productName: productName,
                        productPrice: productPrice,
                        currency: currency,
                        quantity: 1
                     }),
                  });
                  const session = await response.json();
                  const result = await stripe.redirectToCheckout({ sessionId: session.id });
                  if (result.error) alert(result.error.message);
               } catch (error) {
                  console.error('Error:', error);
                  alert('<?php echo ($current_language == 'es') ? "Ocurrió un error. Por favor intente de nuevo." : "An error occurred. Please try again."; ?>');
               } finally {
                  button.innerHTML = '<?php echo ($current_language == 'es') ? "Comprar Ahora" : "Buy Now"; ?>';
                  button.disabled = false;
               }
            });
         });
         */
      });
   </script>
</body>
</html>