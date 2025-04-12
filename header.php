<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '
      <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg z-50 animate__animated animate__fadeInDown">
         <span>' . htmlspecialchars($msg) . '</span>
         <button onclick="this.parentElement.style.display=\'none\'" class="float-right text-red-500 hover:text-red-700">×</button>
      </div>
      ';
   }
}
?>

<header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white shadow-lg fixed top-0 left-0 w-full z-40">
   <div class="container mx-auto px-4">
      <!-- Header Top -->
      <!--<div class="flex justify-between items-center py-2 border-b border-gray-700">
         <div class="flex space-x-4">
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-linkedin"></i></a>
         </div>
         <p class="text-sm">
            New? <a href="login.php" class="text-yellow-400 hover:underline">Login</a> | 
            <a href="register.php" class="text-yellow-400 hover:underline">Register</a>
         </p>
      </div>-->

      <!-- Header Main -->
      <div class="flex justify-between items-center py-4">
         <a href="home.php" class="text-2xl font-bold font-[Playfair Display] text-yellow-400">Arteneer</a>

         <nav class="hidden md:flex space-x-6">
            <a href="home.php" class="text-gray-300 hover:text-white font-medium transition">Home</a>
            <a href="about.php" class="text-gray-300 hover:text-white font-medium transition">About</a>
            <a href="shop.php" class="text-gray-300 hover:text-white font-medium transition">Shop</a>
            <a href="contact.php" class="text-gray-300 hover:text-white font-medium transition">Contact</a>
            <a href="orders.php" class="text-gray-300 hover:text-white font-medium transition">Orders</a>
         </nav>

         <div class="flex items-center space-x-4">
            <button id="menu-btn" class="md:hidden text-2xl text-gray-300 hover:text-white focus:outline-none">
               <i class="fas fa-bars"></i>
            </button>
            <a href="search_page.php" class="text-2xl text-gray-300 hover:text-white transition">
               <i class="fas fa-search"></i>
            </a>
            <button id="user-btn" class="text-2xl text-gray-300 hover:text-white focus:outline-none">
               <i class="fas fa-user"></i>
            </button>
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number);
            ?>
            <a href="cart.php" class="text-2xl text-gray-300 hover:text-white transition relative">
               <i class="fas fa-shopping-cart"></i>
               <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"><?php echo $cart_rows_number; ?></span>
            </a>
         </div>

         <!-- User Dropdown -->
         <div class="user-box hidden absolute top-16 right-4 bg-white text-gray-900 rounded-lg shadow-lg p-4 w-64 z-50 animate__animated animate__fadeIn">
            <p class="text-sm"><span class="font-semibold">Username:</span> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p class="text-sm mt-2"><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <a href="logout.php" class="block mt-4 bg-red-500 text-white text-center py-2 rounded hover:bg-red-600 transition">Logout</a>
         </div>
      </div>
   </div>

   <!-- Mobile Menu (Hidden by Default) -->
   <nav class="md:hidden bg-gray-900 text-white py-4 px-4 space-y-4 hidden" id="mobile-menu">
      <a href="home.php" class="block text-gray-300 hover:text-white font-medium">Home</a>
      <a href="about.php" class="block text-gray-300 hover:text-white font-medium">About</a>
      <a href="shop.php" class="block text-gray-300 hover:text-white font-medium">Shop</a>
      <a href="contact.php" class="block text-gray-300 hover:text-white font-medium">Contact</a>
      <a href="orders.php" class="block text-gray-300 hover:text-white font-medium">Orders</a>
   </nav>
</header>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      const menuBtn = document.getElementById('menu-btn');
      const mobileMenu = document.getElementById('mobile-menu');
      const userBtn = document.getElementById('user-btn');
      const userBox = document.querySelector('.user-box');

      menuBtn.addEventListener('click', () => {
         mobileMenu.classList.toggle('hidden');
      });

      userBtn.addEventListener('click', () => {
         userBox.classList.toggle('hidden');
      });

      // Close user box when clicking outside
      document.addEventListener('click', (e) => {
         if (!userBtn.contains(e.target) && !userBox.contains(e.target)) {
            userBox.classList.add('hidden');
         }
      });
   });
</script>