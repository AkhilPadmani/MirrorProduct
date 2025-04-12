<?php
include 'config.php';

if(isset($_POST['submit'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $user_type = $_POST['user_type'];

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'User already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password not matched!';
      }else{
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpass', '$user_type')") or die('query failed');
         $message[] = 'Registered successfully!';
         header('location:login.php');
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
   <title>Register | Modern Design</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/login.css">
</head>
<body>
   <div class="register-container">
      <div class="register-left">
         <div class="register-header">
            <h1>Create Account</h1>
            <p>Join our community today</p>
         </div>
         
         <?php
         if(isset($message)){
            foreach($message as $message){
               echo '
               <div class="message">
                  <span>'.$message.'</span>
                  <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
               </div>
               ';
            }
         }
         ?>
         
         <form class="register-form" action="" method="post">
            <div class="form-group">
               <label for="name">Full Name</label>
               <input type="text" id="name" name="name" placeholder="Enter your full name" required>
               <i class="fas fa-user"></i>
            </div>
            <div class="form-group">
               <label for="email">Email</label>
               <input type="email" id="email" name="email" placeholder="Enter your email" required>
               <i class="fas fa-envelope"></i>
            </div>
            <div class="form-group">
               <label for="password">Password</label>
               <input type="password" id="password" name="password" placeholder="Enter your password" required>
               <i class="fas fa-lock"></i>
            </div>
            <div class="form-group">
               <label for="cpassword">Confirm Password</label>
               <input type="password" id="cpassword" name="cpassword" placeholder="Confirm your password" required>
               <i class="fas fa-lock"></i>
            </div>
            <div class="form-group">
               <label for="user_type">Account Type</label>
               <div class="select-wrapper">
                  <select name="user_type" id="user_type" required>
                     <option value="user">User</option>
                     <option value="admin">Admin</option>
                  </select>
                  <i class="fas fa-chevron-down"></i>
               </div>
            </div>
            <button type="submit" name="submit" class="register-btn">Register Now</button>
            <div class="register-footer">
               <p>Already have an account? <a href="login.php">Login now</a></p>
            </div>
         </form>
      </div>
      <div class="register-right">
         <div class="right-content">
            <h2>Welcome Back!</h2>
            <p>If you already have an account, just sign in.</p>
            <a href="login.php" class="login-btn">Sign In</a>
         </div>
         <div class="right-image">
            <div class="shape"></div>
            <div class="shape2"></div>
         </div>
      </div>
   </div>
   
   <script src="js/login.js"></script>
</body>
</html>