<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arteneer | About Us</title>
    <meta name="description" content="Learn about Arteneer’s mission to craft premium, minimalist mirrors for modern spaces.">

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

        /* About Section */
        .about {
            padding: 6rem 0;
            background: var(--white);
        }

        .about .flex {
            display: flex;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            gap: 3rem;
            align-items: center;
            animation: fadeInUp 1s ease-out;
        }

        .about .image {
            flex: 1;
            min-height: 300px;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.5s ease;
        }

        .about .image:hover {
            transform: scale(1.02);
        }

        .about .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about .content {
            flex: 1;
        }

        .about .content h3 {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .about .content p {
            font-size: 1.125rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .about .content .btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: #000000FF;
            color: var(--white);
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 11px;
            transition: var(--transition);
        }

        .about .content .btn:hover {
            background: #2B2A2AFF;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        /* Reviews Section */
        .reviews {
            padding: 6rem 0;
            background: var(--light-bg);
        }

        .reviews .title {
            text-align: center;
            font-family: var(--font-heading);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 4rem;
            position: relative;
        }

        .reviews .title::after {
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

        .reviews .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .reviews .box {
            background: var(--white);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            animation: slideIn 0.5s ease-out;
        }

        .reviews .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .reviews .box img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 1.5rem;
            object-fit: cover;
            border: 3px solid var(--accent-color);
        }

        .reviews .box p {
            font-size: 1rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .reviews .box .stars {
            margin-bottom: 1rem;
        }

        .reviews .box .stars i {
            color: var(--accent-color);
            font-size: 1.25rem;
        }

        .reviews .box h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Authors Section */
        .authors {
            padding: 6rem 0;
            background: var(--white);
        }

        .authors .title {
            text-align: center;
            font-family: var(--font-heading);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 4rem;
            position: relative;
        }

        .authors .title::after {
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

        .authors .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .authors .box {
            background: var(--light-bg);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            animation: slideIn 0.5s ease-out;
        }

        .authors .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            background: #fff7ed;
        }

        .authors .box img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 1.5rem;
            object-fit: cover;
            border: 3px solid var(--accent-color);
            transition: transform 0.3s ease;
        }

        .authors .box:hover img {
            transform: scale(1.1);
        }

        .authors .box .share {
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .authors .box .share a {
            color: var(--secondary-color);
            font-size: 1.25rem;
            transition: var(--transition);
        }

        .authors .box .share a:hover {
            color: var(--accent-color);
            transform: scale(1.2);
        }

        .authors .box h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
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
            .about .flex { flex-direction: column; }
            .about .image { width: 100%; min-height: 400px; margin-bottom: 2rem; }
            .heading h3 { font-size: 2.5rem; }
        }

        @media (max-width: 768px) {
            .heading h3 { font-size: 2rem; }
            .about .content h3, .reviews .title, .authors .title { font-size: 2rem; }
            .about .content p, .reviews .box p, .authors .box p { font-size: 1rem; }
            .reviews .box-container, .authors .box-container { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
        }

        @media (max-width: 576px) {
            .heading h3 { font-size: 1.75rem; }
            .about .content h3, .reviews .title, .authors .title { font-size: 1.75rem; }
            .about .image { min-height: 300px; }
            .reviews .box img, .authors .box img { width: 80px; height: 80px; }
        }
    </style>
</head>
<body>
    
    <?php include 'header.php'; ?>

    <!-- Heading Section -->
    <div class="heading">
        <h3>About Us</h3>
        <p><a href="home.php">Home</a> / About</p>
    </div>

    <!-- About Section -->
    <section class="about">
        <div class="flex">
            <div class="image">
                <img src="images/mirror4.jpg" alt="Arteneer Craftsmanship">
            </div>
            <div class="content">
                <h3>Why Choose Arteneer?</h3>
                <p>At Arteneer, we believe mirrors are more than reflections—they’re statements of style and craftsmanship. Our team of skilled artisans handcrafts each piece with premium materials, blending minimalist design with timeless elegance.</p>
                <p>With a commitment to sustainability and quality, we create mirrors that elevate your space and stand the test of time. Discover the Arteneer difference today.</p>
                <a href="contact.php" class="btn">Contact Us</a>
            </div>
        </div>
    </section>

    <!-- Reviews Section (Reduced to 2 Reviews) -->
    <section class="reviews">
        <h1 class="title">Client Reviews</h1>
        <div class="box-container">
            <div class="box">
                <img src="images/apple.jpg" alt="Client 1">
                <p>Arteneer mirrors transformed my living space! The quality is unmatched, and the design is simply stunning.</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>John Doe</h3>
            </div>

            <div class="box">
                <img src="images/apple.jpg" alt="Client 2">
                <p>Absolutely love the elegance and craftsmanship. Shipping was fast and secure—highly recommend!</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h3>Jane Smith</h3>
            </div>
        </div>
    </section>

    <!-- Authors Section -->
    <section class="authors">
        <h1 class="title">Our Artisans</h1>
        <div class="box-container">
            <div class="box">
                <img src="images/apple.jpg" alt="Author 1">
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
                <h3>Alex Carter</h3>
            </div>

            <div class="box">
                <img src="images/apple.jpg" alt="Author 2">
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
                <h3>Mia Patel</h3>
            </div>

            <div class="box">
                <img src="images/apple.jpg" alt="Author 3">
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
                <h3>Lucas Kim</h3>
            </div>

            <div class="box">
                <img src="images/apple.jpg" alt="Author 4">
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
                <h3>Sofia Nguyen</h3>
            </div>

            <div class="box">
                <img src="images/apple.jpg" alt="Author 5">
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
                <h3>Ethan Brooks</h3>
            </div>

            <div class="box">
                <img src="images/apple.jpg" alt="Author 6">
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
                <h3>Olivia Hayes</h3>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation delays for boxes
            document.querySelectorAll('.reviews .box').forEach((box, index) => {
                box.style.animationDelay = `${index * 0.1}s`;
            });

            document.querySelectorAll('.authors .box').forEach((box, index) => {
                box.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>