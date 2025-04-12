<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message animate__animated animate__fadeInDown">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         <div class="progress-bar"></div>
      </div>
      ';
   }
}
?>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    // Toggle user dropdown
    const userBtn = document.getElementById('user-btn');
    const accountBox = document.querySelector('.user-dropdown');
    
    if (userBtn && accountBox) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            accountBox.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !accountBox.contains(e.target)) {
                userBtn.classList.remove('active');
                accountBox.classList.remove('active');
            }
        });
    }
    
    // Auto-hide messages after 3 seconds
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        setTimeout(() => {
            message.classList.add('animate__fadeOut');
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 3000);
    });
    
    // Add scroll effect to header
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.admin-header');
        if (header) {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    });
    
    // Mobile menu toggle (if you add mobile menu later)
    const menuBtn = document.getElementById('menu-btn');
    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            document.querySelector('.main-nav').classList.toggle('active');
        });
    }
});
</script>

<header class="admin-header">
   <div class="header-container">
      <!-- Logo -->
      <a href="admin_page.php" class="logo">
         <div class="logo-icon">
            <i class="fas fa-shield-alt"></i>
         </div>
         <div class="logo-text">
            <span class="logo-main">Arteneer</span>
         </div>
      </a>

      <!-- Main Navigation -->
      <nav class="main-nav">
         <ul class="nav-list">
            <li class="nav-item">
               <a href="admin_page.php" class="nav-link">
                  <!--<i class="fas fa-home"></i>-->
                  <span>Dashboard</span>
               </a>
            </li>
            <li class="nav-item">
               <a href="admin_products.php" class="nav-link">
                  <!--<i class="fas fa-box-open"></i>-->
                  <span>Products</span>
               </a>
            </li>
            <li class="nav-item">
               <a href="admin_orders.php" class="nav-link">
                  <!--<i class="fas fa-shopping-cart"></i>-->
                  <span>Orders</span>
               </a>
            </li>
            <li class="nav-item">
               <a href="admin_users.php" class="nav-link">
                  <!--<i class="fas fa-users"></i>-->
                  <span>Users</span>
               </a>
            </li>
            <li class="nav-item">
               <a href="admin_contacts.php" class="nav-link">
                  <!--<i class="fas fa-envelope"></i>-->
                  <span>Messages</span>
               </a>
            </li>
         </ul>
      </nav>

      <!-- User Controls -->
      <div class="user-controls">
         <div class="notification-bell">
            <i class="fas fa-bell"></i>
            <span class="notification-count">3</span>
         </div>
         
         <div class="user-profile" id="user-btn">
            <div class="avatar">
               <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-info">
               <span class="user-name"><?php echo $_SESSION['admin_name']; ?></span>
               <span class="user-role">Administrator</span>
            </div>
            <i class="fas fa-chevron-down dropdown-arrow"></i>
         </div>
      </div>

      <!-- User Dropdown -->
      <div class="user-dropdown" id="account-box">
         <div class="dropdown-header">
            <div class="dropdown-avatar">
               <i class="fas fa-user-circle"></i>
            </div>
            <div class="dropdown-user-info">
               <h4><?php echo $_SESSION['admin_name']; ?></h4>
               <p><?php echo $_SESSION['admin_email']; ?></p>
            </div>
         </div>
         
         <div class="dropdown-menu">
            <a href="#" class="dropdown-item">
               <i class="fas fa-user-cog"></i>
               <span>Account Settings</span>
            </a>
            <a href="#" class="dropdown-item">
               <i class="fas fa-cog"></i>
               <span>Preferences</span>
            </a>
            <a href="#" class="dropdown-item">
               <i class="fas fa-question-circle"></i>
               <span>Help Center</span>
            </a>
            
            <div class="dropdown-divider"></div>
            
            <a href="logout.php" class="dropdown-item logout">
               <i class="fas fa-sign-out-alt"></i>
               <span>Logout</span>
            </a>
         </div>
      </div>
   </div>
</header>