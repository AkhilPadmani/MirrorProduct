<?php
include 'config.php';
session_start();

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $row = mysqli_fetch_assoc($select_users);

      if($row['user_type'] == 'admin'){
         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');
      }elseif($row['user_type'] == 'user'){
         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');
      }
   }else{
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login | Modern Design</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/login.css">
</head>
<body>
   <div class="login-container">
      <div class="login-left">
         <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Please login to your account</p>
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
         
         <form class="login-form" action="" method="post">
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
            <button type="submit" name="submit" class="login-btn">Login</button>
            <div class="login-footer">
               <p>Don't have an account? <a href="register.php">Register now</a></p>
               <a href="#" class="forgot-password">Forgot password?</a>
            </div>
         </form>
      </div>
      <div class="login-right">
         <div class="right-content">
            <h2>New Here?</h2>
            <p>Sign up and discover a great community of users!</p>
            <a href="register.php" class="signup-btn">Sign Up</a>
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