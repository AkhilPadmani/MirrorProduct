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

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = ($current_language == 'es') ? '¡Cantidad del carrito actualizada!' : 'Cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="<?php echo $current_language; ?>">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo ($current_language == 'es') ? 'Carrito' : 'Cart'; ?> | Arteneer</title>

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
         --primary-color: #1f2937;
         --secondary-color: #4b5563;
         --accent-color: #f59e0b;
         --light-bg: #f9fafb;
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

      .shopping-cart {
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

      .box-container {
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 2rem;
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
         gap: 2rem;
         animation: fadeInUp 1s ease-out;
      }

      .box {
         background: var(--white);
         border-radius: 1.5rem;
         overflow: hidden;
         box-shadow: var(--shadow);
         transition: var(--transition), transform 0.5s ease;
         position: relative;
         border: 1px solid rgba(0, 0, 0, 0.05);
         padding: 1.5rem;
         text-align: center;
      }

      .box:hover {
         transform: translateY(-10px) scale(1.02);
         box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      }

      .box img {
         width: 100%;
         height: 300px;
         object-fit: cover;
         border-radius: 1rem;
         margin-bottom: 1rem;
      }

      .box .name {
         font-size: 1.5rem;
         font-weight: 600;
         color: var(--primary-color);
         margin-bottom: 0.75rem;
      }

      .box .price {
         font-size: 1.75rem;
         font-weight: 700;
         color: var(--accent-color);
         margin-bottom: 1rem;
      }

      .box form {
         margin: 1rem 0;
      }

      .box input[type="number"] {
         width: 80px;
         padding: 0.75rem;
         border: 1px solid var(--secondary-color);
         border-radius: 9999px;
         text-align: center;
         font-size: 1rem;
         background: var(--light-bg);
         transition: border-color 0.3s ease;
      }

      .box input[type="number"]:focus {
         border-color: var(--accent-color);
         outline: none;
      }

      .box input[type="submit"] {
         padding: 0.75rem 1.5rem;
         background: var(--accent-color);
         color: var(--white);
         font-size: 1rem;
         font-weight: 600;
         border-radius: 9999px;
         transition: var(--transition);
         cursor: pointer;
         border: none;
      }

      .box input[type="submit"]:hover {
         background: #d97706;
         transform: scale(1.05);
         box-shadow: var(--shadow);
      }

      .box .sub-total {
         font-size: 1.125rem;
         color: var(--secondary-color);
         margin-top: 1rem;
      }

      .box .sub-total span {
         color: var(--primary-color);
         font-weight: 600;
      }

      .box .fas {
         position: absolute;
         top: 1rem;
         right: 1rem;
         font-size: 1.5rem;
         color: var(--secondary-color);
         cursor: pointer;
         transition: color 0.3s ease;
      }

      .box .fas:hover {
         color: var(--accent-color);
      }

      .empty {
         text-align: center;
         font-size: 1.5rem;
         color: var(--secondary-color);
         padding: 4rem 0;
         animation: fadeIn 1s ease-out;
      }

      .cart-total {
         max-width: 1280px;
         margin: 2rem auto 0;
         padding: 2rem;
         background: var(--light-bg);
         border-radius: 1.5rem;
         box-shadow: var(--shadow);
         text-align: center;
         animation: fadeInUp 1s ease-out;
      }

      .cart-total p {
         font-size: 1.75rem;
         font-weight: 600;
         color: var(--primary-color);
         margin-bottom: 1.5rem;
      }

      .cart-total p span {
         color: var(--accent-color);
         font-size: 2rem;
      }

      .cart-total .flex {
         display: flex;
         gap: 1rem;
         justify-content: center;
         flex-wrap: wrap;
      }

      .cart-total a {
         padding: 0.75rem 1.5rem;
         font-size: 1rem;
         font-weight: 600;
         border-radius: 9999px;
         transition: var(--transition);
         text-decoration: none;
         display: inline-block;
      }

      .option-btn {
         background: var(--secondary-color);
         color: var(--white);
      }

      .option-btn:hover {
         background: #374151;
         transform: scale(1.05);
         box-shadow: var(--shadow);
      }

      .btn {
         background: var(--accent-color);
         color: var(--white);
      }

      .btn:hover {
         background: #d97706;
         transform: scale(1.05);
         box-shadow: var(--shadow);
      }

      .delete-btn {
         background: #ef4444;
         color: var(--white);
         padding: 0.75rem 1.5rem;
      }

      .delete-btn:hover {
         background: #dc2626;
         transform: scale(1.05);
         box-shadow: var(--shadow);
      }

      .disabled {
         opacity: 0.6;
         cursor: not-allowed;
         pointer-events: none;
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

      /* Responsive Adjustments */
      @media (max-width: 1024px) {
         .shop-controls { flex-direction: column; align-items: flex-start; }
         .box-container { grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); }
      }

      @media (max-width: 768px) {
         .heading h3 { font-size: 2.5rem; }
         .shop-controls .title { font-size: 2rem; }
         .control-group { width: 100%; justify-content: space-between; }
         .box { padding: 1rem; }
         .box img { height: 250px; }
         .box .name { font-size: 1.25rem; }
         .box .price { font-size: 1.5rem; }
         .cart-total p { font-size: 1.5rem; }
         .cart-total p span { font-size: 1.75rem; }
      }

      @media (max-width: 576px) {
         .heading h3 { font-size: 2rem; }
         .box-container { grid-template-columns: 1fr; }
         .box img { height: 200px; }
         .box .name { font-size: 1rem; }
         .box .price { font-size: 1.25rem; }
         .cart-total p { font-size: 1.25rem; }
         .cart-total p span { font-size: 1.5rem; }
      }
   </style>
</head>
<body>
   
   <?php include 'header.php'; ?>

   <!-- Heading Section -->
   <div class="heading">
      <h3><?php echo ($current_language == 'es') ? 'Carrito de Compras' : 'Shopping Cart'; ?></h3>
      <p><a href="home.php"><?php echo ($current_language == 'es') ? 'Inicio' : 'Home'; ?></a> / <?php echo ($current_language == 'es') ? 'Carrito' : 'Cart'; ?></p>
   </div>

   <!-- Shop Section -->
   <section class="shopping-cart">
      <div class="shop-controls">
         <h1 class="title"><?php echo ($current_language == 'es') ? 'Productos Añadidos' : 'Products Added'; ?></h1>
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

      <div class="box-container">
         <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
               $original_price = $fetch_cart['price'];
               $converted_price = $original_price * $currencies[$current_currency]['rate'];
               $formatted_price = $currencies[$current_currency]['symbol'] . number_format($converted_price, 2);
               $sub_total = $converted_price * $fetch_cart['quantity'];
         ?>
         <div class="box">
            <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('<?php echo ($current_language == 'es') ? '¿Eliminar esto del carrito?' : 'Delete this from cart?'; ?>');"></a>
            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_cart['name']; ?></div>
            <div class="price"><?php echo $formatted_price; ?></div>
            <form action="" method="post">
               <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
               <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="qty">
               <input type="submit" name="update_cart" value="<?php echo ($current_language == 'es') ? 'Actualizar' : 'Update'; ?>" class="option-btn">
            </form>
            <div class="sub-total"> <?php echo ($current_language == 'es') ? 'Subtotal:' : 'Sub total:'; ?> <span><?php echo $currencies[$current_currency]['symbol'] . number_format($sub_total, 2); ?></span> </div>
         </div>
         <?php
            $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty">' . (($current_language == 'es') ? '¡Tu carrito está vacío!' : 'Your cart is empty!') . '</p>';
         }
         ?>
      </div>

      <div style="margin-top: 2rem; text-align:center;">
         <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 0)?'':'disabled'; ?>" onclick="return confirm('<?php echo ($current_language == 'es') ? '¿Eliminar todo del carrito?' : 'Delete all from cart?'; ?>');"><?php echo ($current_language == 'es') ? 'Eliminar Todo' : 'Delete All'; ?></a>
      </div>

      <div class="cart-total">
         <p><?php echo ($current_language == 'es') ? 'Total General:' : 'Grand Total:'; ?> <span><?php echo $currencies[$current_currency]['symbol'] . number_format($grand_total, 2); ?></span></p>
         <div class="flex">
            <a href="shop.php" class="option-btn"><?php echo ($current_language == 'es') ? 'Continuar Comprando' : 'Continue Shopping'; ?></a>
            <a href="checkout.php" class="btn <?php echo ($grand_total > 0)?'':'disabled'; ?>"><?php echo ($current_language == 'es') ? 'Proceder al Pago' : 'Proceed to Checkout'; ?></a>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Product Card Animation Delay
         const boxes = document.querySelectorAll('.box');
         boxes.forEach((box, index) => {
            box.style.animationDelay = `${index * 0.1}s`;
         });
      });
   </script>
</body>
</html>