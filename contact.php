<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['send'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'Message already sent!';
    } else {
        mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
        $message[] = 'Message sent successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Arteneer | Contact Us</title>
   <meta name="description" content="Get in touch with Arteneer for inquiries, support, or feedback about our premium mirrors.">

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

      /* Contact Section */
      .contact {
         padding: 6rem 0;
         background: var(--white);
         position: relative;
      }

      .contact form {
         max-width: 700px;
         margin: 0 auto;
         padding: 2rem;
         background: var(--light-bg);
         border-radius: 1.5rem;
         box-shadow: var(--shadow);
         animation: fadeInUp 1s ease-out;
      }

      .contact h3 {
         font-family: var(--font-heading);
         font-size: 2.5rem;
         font-weight: 700;
         color: var(--primary-color);
         text-align: center;
         margin-bottom: 2rem;
         position: relative;
      }

      .contact h3::after {
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

      .contact .box {
         width: 100%;
         padding: 1rem 1.5rem;
         margin-bottom: 1.5rem;
         border: 1px solid var(--secondary-color);
         border-radius: 0.5rem;
         font-size: 1rem;
         color: var(--primary-color);
         background: var(--white);
         transition: var(--transition);
      }

      .contact .box:focus {
         border-color: var(--accent-color);
         box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
         outline: none;
      }

      .contact textarea.box {
         resize: vertical;
         min-height: 150px;
      }

      .contact .btn {
         display: block;
         width: 100%;
         padding: 1rem;
         background: var(--accent-color);
         color: var(--white);
         font-size: 1.125rem;
         font-weight: 600;
         border-radius: 9999px;
         text-align: center;
         transition: var(--transition);
         cursor: pointer;
      }

      .contact .btn:hover {
         background: #d97706;
         transform: translateY(-3px);
         box-shadow: var(--shadow);
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

      /* Responsive Adjustments */
      @media (max-width: 768px) {
         .heading h3 { font-size: 2.5rem; }
         .contact h3 { font-size: 2rem; }
         .contact form { padding: 1.5rem; }
         .contact .box { padding: 0.75rem; font-size: 0.875rem; }
         .contact .btn { font-size: 1rem; }
      }

      @media (max-width: 576px) {
         .heading h3 { font-size: 2rem; }
         .contact h3 { font-size: 1.75rem; }
         .contact form { padding: 1rem; }
         .contact .box { padding: 0.5rem; }
      }
   </style>
</head>
<body>
   
   <?php include 'header.php'; ?>

   <!-- Heading Section -->
   <div class="heading">
      <h3>Contact Us</h3>
      <p><a href="home.php">Home</a> / Contact</p>
   </div>

   <!-- Contact Section -->
   <section class="contact">
      <form action="" method="post">
         <h3>Let’s Connect!</h3>
         <input type="text" name="name" required placeholder="Your Name" class="box">
         <input type="email" name="email" required placeholder="Your Email" class="box">
         <input type="number" name="number" required placeholder="Your Phone Number" class="box">
         <textarea name="message" class="box" placeholder="Your Message" cols="30" rows="10"></textarea>
         <input type="submit" value="Send Message" name="send" class="btn">
      </form>
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