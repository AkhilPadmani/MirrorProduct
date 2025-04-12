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

if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['state'].', '. $_POST['country'].' - '. $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = ($current_language == 'es') ? '¡Tu carrito está vacío!' : 'Your cart is empty!';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = ($current_language == 'es') ? '¡Pedido ya realizado!' : 'Order already placed!';
        } else {
            // Dynamically assign a delivery partner
            $partner_query = mysqli_query($conn, "SELECT * FROM `delivery_partners` WHERE availability = 'Available' LIMIT 1") or die('query failed');
            if (mysqli_num_rows($partner_query) > 0) {
                $partner = mysqli_fetch_assoc($partner_query);
                $delivery_partner_id = $partner['id'];

                // Insert order with delivery partner
                mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, delivery_partner_id, status) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', '$delivery_partner_id', 'Pending')") or die('query failed');

                // Update delivery partner availability to 'Busy'
                mysqli_query($conn, "UPDATE `delivery_partners` SET availability = 'Busy' WHERE id = '$delivery_partner_id'") or die('query failed');

                $message[] = ($current_language == 'es') ? '¡Pedido realizado con éxito! Socio de entrega asignado: ' . $partner['name'] : 'Order placed successfully! Delivery partner assigned: ' . $partner['name'];
                mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            } else {
                $message[] = ($current_language == 'es') ? '¡No hay socios de entrega disponibles en este momento!' : 'No delivery partners available at the moment!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $current_language; ?>">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo ($current_language == 'es') ? 'Finalizar Compra' : 'Checkout'; ?> | Arteneer</title>

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

      .display-order, .checkout {
         max-width: 1280px;
         margin: 0 auto;
         padding: 0 2rem;
         background: var(--white);
         border-radius: 1.5rem;
         box-shadow: var(--shadow);
         margin-bottom: 2rem;
         animation: fadeInUp 1s ease-out;
      }

      .display-order p {
         font-size: 1.25rem;
         color: var(--primary-color);
         margin-bottom: 1rem;
         padding: 1rem;
         border-bottom: 1px solid var(--light-bg);
      }

      .display-order p span {
         color: var(--accent-color);
         font-weight: 600;
         margin-left: 1rem;
      }

      .grand-total {
         text-align: center;
         font-size: 1.75rem;
         font-weight: 600;
         color: var(--primary-color);
         padding: 1.5rem;
         background: var(--light-bg);
         border-radius: 1rem;
         margin-top: 1rem;
      }

      .grand-total span {
         color: var(--accent-color);
         font-size: 2rem;
      }

      .checkout {
         padding: 2rem;
      }

      .checkout h3 {
         font-family: var(--font-heading);
         font-size: 2rem;
         font-weight: 700;
         color: var(--primary-color);
         text-align: center;
         margin-bottom: 2rem;
      }

      .checkout-form {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         gap: 1.5rem;
         max-width: 900px;
         margin: 0 auto;
         padding: 2rem;
         background: var(--light-bg);
         border-radius: 1.5rem;
         box-shadow: var(--shadow);
      }

      .form-group {
         background: var(--white);
         padding: 1.5rem;
         border-radius: 1rem;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
         transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .form-group:hover {
         transform: translateY(-5px);
         box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      }

      .form-group label {
         display: block;
         font-size: 1rem;
         font-weight: 500;
         color: var(--secondary-color);
         margin-bottom: 0.5rem;
      }

      .form-group input, .form-group select {
         width: 100%;
         padding: 0.875rem;
         border: 2px solid var(--light-bg);
         border-radius: 0.75rem;
         font-size: 1rem;
         background: var(--white);
         color: var(--primary-color);
         transition: border-color 0.3s ease, box-shadow 0.3s ease;
      }

      .form-group input:focus, .form-group select:focus {
         border-color: var(--accent-color);
         outline: none;
         box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
      }

      .btn {
         padding: 1rem 2rem;
         background: var(--accent-color);
         color: var(--white);
         font-size: 1.125rem;
         font-weight: 600;
         border-radius: 0.75rem;
         transition: var(--transition);
         width: 100%;
         max-width: 250px;
         margin: 2rem auto 0;
         display: block;
         cursor: pointer;
         box-shadow: var(--shadow);
      }

      .btn:hover {
         background: #d97706;
         transform: scale(1.05);
         box-shadow: 0 15px 25px rgba(245, 158, 11, 0.3);
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

      /* Responsive Adjustments */
      @media (max-width: 1024px) {
         .shop-controls { flex-direction: column; align-items: flex-start; }
         .checkout-form { grid-template-columns: 1fr; }
      }

      @media (max-width: 768px) {
         .heading h3 { font-size: 2.5rem; }
         .shop-controls .title { font-size: 2rem; }
         .control-group { width: 100%; justify-content: space-between; }
         .display-order p { font-size: 1rem; }
         .grand-total { font-size: 1.5rem; }
         .grand-total span { font-size: 1.75rem; }
         .checkout h3 { font-size: 1.75rem; }
         .checkout-form { padding: 1.5rem; }
         .form-group { padding: 1rem; }
      }

      @media (max-width: 576px) {
         .heading h3 { font-size: 2rem; }
         .display-order p { font-size: 0.875rem; }
         .grand-total { font-size: 1.25rem; }
         .grand-total span { font-size: 1.5rem; }
         .checkout h3 { font-size: 1.5rem; }
         .form-group label, .form-group input, .form-group select { font-size: 0.875rem; }
         .btn { font-size: 1rem; padding: 0.75rem 1.5rem; max-width: 200px; }
         .checkout-form { gap: 1rem; }
      }
   </style>
</head>
<body>
   
   <?php include 'header.php'; ?>

   <!-- Heading Section -->
   <div class="heading">
      <h3><?php echo ($current_language == 'es') ? 'Finalizar Compra' : 'Checkout'; ?></h3>
      <p><a href="home.php"><?php echo ($current_language == 'es') ? 'Inicio' : 'Home'; ?></a> / <?php echo ($current_language == 'es') ? 'Finalizar Compra' : 'Checkout'; ?></p>
   </div>

   <!-- Shop Controls -->
   <div class="shop-controls">
      <h1 class="title"><?php echo ($current_language == 'es') ? 'Resumen del Pedido' : 'Order Summary'; ?></h1>
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

   <!-- Display Order Section -->
   <section class="display-order">
      <?php  
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
               $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
               $converted_price = $total_price * $currencies[$current_currency]['rate'];
               $formatted_price = $currencies[$current_currency]['symbol'] . number_format($converted_price, 2);
               $grand_total += $converted_price;
      ?>
      <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo $formatted_price.' x '. $fetch_cart['quantity']; ?>)</span> </p>
      <?php
         }
         }else{
            echo '<p class="empty">' . (($current_language == 'es') ? '¡Tu carrito está vacío!' : 'Your cart is empty!') . '</p>';
         }
      ?>
      <div class="grand-total"> <?php echo ($current_language == 'es') ? 'Total General:' : 'Grand Total:'; ?> <span><?php echo $currencies[$current_currency]['symbol'] . number_format($grand_total, 2); ?></span> </div>
   </section>

   <!-- Checkout Form Section -->
   <section class="checkout">
      <form action="" method="post">
         <h3><?php echo ($current_language == 'es') ? 'Realiza tu Pedido' : 'Place Your Order'; ?></h3>
         <div class="checkout-form">
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Tu Nombre:' : 'Your Name:'; ?></label>
               <input type="text" name="name" required placeholder="<?php echo ($current_language == 'es') ? 'Introduce tu nombre' : 'Enter your name'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Tu Número:' : 'Your Number:'; ?></label>
               <input type="number" name="number" required placeholder="<?php echo ($current_language == 'es') ? 'Introduce tu número' : 'Enter your number'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Tu Correo:' : 'Your Email:'; ?></label>
               <input type="email" name="email" required placeholder="<?php echo ($current_language == 'es') ? 'Introduce tu correo' : 'Enter your email'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Método de Pago:' : 'Payment Method:'; ?></label>
               <select name="method">
                  <option value="cash on delivery"><?php echo ($current_language == 'es') ? 'Efectivo al Entregar' : 'Cash on Delivery'; ?></option>
                  <option value="credit card"><?php echo ($current_language == 'es') ? 'Tarjeta de Crédito' : 'Credit Card'; ?></option>
                  <option value="paypal"><?php echo ($current_language == 'es') ? 'PayPal' : 'PayPal'; ?></option>
                  <option value="paytm"><?php echo ($current_language == 'es') ? 'Paytm' : 'Paytm'; ?></option>
               </select>
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'No. de Piso:' : 'Flat No.:'; ?></label>
               <input type="number" min="0" name="flat" required placeholder="<?php echo ($current_language == 'es') ? 'Ej. No. de Piso' : 'E.g. Flat No.'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Nombre de la Calle:' : 'Street Name:'; ?></label>
               <input type="text" name="street" required placeholder="<?php echo ($current_language == 'es') ? 'Ej. Nombre de la Calle' : 'E.g. Street Name'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Ciudad:' : 'City:'; ?></label>
               <input type="text" name="city" required placeholder="<?php echo ($current_language == 'es') ? 'Ej. Mumbai' : 'E.g. Mumbai'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Estado:' : 'State:'; ?></label>
               <input type="text" name="state" required placeholder="<?php echo ($current_language == 'es') ? 'Ej. Maharashtra' : 'E.g. Maharashtra'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'País:' : 'Country:'; ?></label>
               <input type="text" name="country" required placeholder="<?php echo ($current_language == 'es') ? 'Ej. India' : 'E.g. India'; ?>">
            </div>
            <div class="form-group">
               <label><?php echo ($current_language == 'es') ? 'Código Postal:' : 'Pin Code:'; ?></label>
               <input type="number" min="0" name="pin_code" required placeholder="<?php echo ($current_language == 'es') ? 'Ej. 123456' : 'E.g. 123456'; ?>">
            </div>
         </div>
         <input type="submit" value="<?php echo ($current_language == 'es') ? 'Ordenar Ahora' : 'Order Now'; ?>" class="btn" name="order_btn">
      </form>
   </section>

   <?php include 'footer.php'; ?>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         // Add smooth scroll or form validation if needed
      });
   </script>
</body>
</html>