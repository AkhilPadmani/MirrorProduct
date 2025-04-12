<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// Initialize $message as an empty array if it doesn't exist
if (!isset($message) || !is_array($message)) {
    $message = [];
}

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'Product name already added';
    } else {
        $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) VALUES('$name', '$price', '$image')") or die('query failed');

        if ($add_product_query) {
            if ($image_size > 20000000) {
                $message[] = 'Image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Product added successfully!';
            }
        } else {
            $message[] = 'Product could not be added!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_products.php');
}

if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];

    mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image file size is too large';
        } else {
            mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('uploaded_img/' . $update_old_image);
        }
    }

    header('location:admin_products.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome CDN (Ensure correct version and loading) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Animate.css CDN for animations -->
   <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />-->

    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --accent-color: #0d0d11;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --danger-color: #0d0d11;
            --success-color: #0d0d11;
            --warning-color: #0d0d11;
            --info-color: #0d0d11;
            --border-radius: 10px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f3f4f6;
            font-family: 'Arial', sans-serif;
            margin-top: 70px; /* Adjust for fixed header */
        }

        .header {
            background: linear-gradient(135deg, #4B6CB7, #182848);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 50;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
        }

        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #4B6CB7;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3A5A9A;
            transform: translateY(-2px);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-out;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .product-actions {
            opacity: 1;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .update-btn {
            background-color: #4B6CB7;
        }

        .update-btn:hover {
            background-color: #3A5A9A;
            transform: rotate(15deg);
        }

        .delete-btn {
            background-color: #ef233c;
        }

        .delete-btn:hover {
            background-color: #d63031;
            transform: rotate(15deg);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: zoomIn 0.3s ease-out;
        }

        .modal.active {
            display: flex;
        }

        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        .upload-area:hover {
            border-color: #4B6CB7;
            background-color: rgba(75, 108, 183, 0.1);
        }

        .upload-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 0.5rem;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem; /* Adjusted padding to avoid overlap */
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .search-box input:focus {
            border-color: #4B6CB7;
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

 /* Admin Header Styles */
 .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: var(--shadow);
            z-index: 1000;
            padding: 0 2rem;
            transition: all 0.3s ease;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: translateX(5px);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--primary-color);
            font-size: 1.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-main {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            line-height: 1;
        }

        .logo-sub {
            font-size: 0.8rem;
            color: #dbeafe;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .main-nav {
            flex: 1;
            margin: 0 2rem;
        }

        .nav-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            margin: 0 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #dbeafe;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease;
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .user-controls {
            display: flex;
            align-items: center;
        }

        .notification-bell {
            position: relative;
            margin-right: 1.5rem;
            cursor: pointer;
            color: #dbeafe;
            transition: all 0.3s ease;
            font-size: 1.25rem;
        }

        .notification-bell:hover {
            color: white;
            transform: translateY(-2px);
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 2rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }

        .user-profile:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .avatar {
            width: 36px;
            height: 36px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.8rem;
            color: var(--primary-color);
            font-size: 1.25rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
        }

        .user-role {
            font-size: 0.7rem;
            color: #dbeafe;
        }

        .dropdown-arrow {
            margin-left: 0.5rem;
            font-size: 0.8rem;
            color: #dbeafe;
            transition: transform 0.3s ease;
        }

        .user-profile.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        .user-dropdown {
            position: absolute;
            top: 70px;
            right: 2rem;
            width: 280px;
            background: white;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .user-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .dropdown-avatar {
            width: 50px;
            height: 50px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--primary-color);
            font-size: 2rem;
        }

        .dropdown-user-info h4 {
            margin: 0;
            font-size: 1rem;
            color: var(--text-color);
        }

        .dropdown-user-info p {
            margin: 0.2rem 0 0;
            font-size: 0.8rem;
            color: #64748b;
        }

        .dropdown-menu {
            padding: 0.5rem 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .dropdown-item i {
            margin-right: 1rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            color: var(--primary-color);
            padding-left: 1.8rem;
        }

        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0.5rem 0;
        }

        .dropdown-item.logout {
            color: var(--danger-color);
        }

        .dropdown-item.logout:hover {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

<?php include 'admin_header.php'; ?>

<div class="container mx-auto mt-16">
    <?php
    // Display messages if they exist
   
    ?>

    <div class="card animate__animated animate__fadeInLeft">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Add New Product</h2>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <div class="mt-1 relative">
                    <input type="text" name="name" id="name" placeholder="Enter product name" required class="w-full px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                <div class="mt-1 relative">
                    <input type="number" name="price" id="price" min="0" step="0.01" placeholder="Enter product price" required class="w-full px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-dollar-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <div class="mt-1">
                    <label for="image" class="upload-area flex flex-col items-center justify-center cursor-pointer">
                        <input type="file" name="image" id="image" accept="image/jpg, image/jpeg, image/png" required class="hidden">
                        <div class="upload-preview">
                            <i class="fas fa-cloud-upload-alt text-3xl text-blue-500 mb-2"></i>
                            <p class="text-gray-500">Click to upload image</p>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" name="add_product" class="btn-primary inline-flex items-center">
                Add Product <i class="fas fa-plus ml-2"></i>
            </button>
        </form>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Your Products</h2>
            <div class="relative search-box">
                <input type="text" placeholder="Search products..." id="product-search" class="w-full px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <div class="product-grid">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                    <div class="product-card" data-name="<?php echo strtolower($fetch_products['name']); ?>">
                        <div class="product-image">
                            <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
                            <div class="product-actions">
                                <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="action-btn update-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Delete this product?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800"><?php echo $fetch_products['name']; ?></h3>
                            <p class="text-xl font-bold text-blue-600">$<?php echo number_format($fetch_products['price'], 2); ?></p>
                            <p class="text-sm text-gray-500">ID: <?php echo $fetch_products['id']; ?></p>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="text-center py-8 bg-gray-100 rounded-lg animate__animated animate__fadeIn"><i class="fas fa-box-open text-4xl text-gray-400 mb-2"></i><p class="text-gray-600">No products added yet!</p></div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {
        ?>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Update Product</h2>
                        <button onclick="document.getElementById('editModal').classList.remove('active')" class="text-gray-500 hover:text-red-500 transition duration-300 close-modal"><i class="fas fa-times"></i></button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">

                        <div class="text-center">
                            <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="Current Image" class="mx-auto max-w-full max-h-48 rounded-lg mb-2">
                            <span class="text-gray-500">Current Image</span>
                        </div>

                        <div>
                            <label for="update_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <div class="mt-1 relative">
                                <input type="text" name="update_name" id="update_name" value="<?php echo $fetch_update['name']; ?>" required class="w-full px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <i class="fas fa-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label for="update_price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                            <div class="mt-1 relative">
                                <input type="number" name="update_price" id="update_price" min="0" step="0.01" value="<?php echo $fetch_update['price']; ?>" required class="w-full px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <i class="fas fa-dollar-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label for="update_image" class="block text-sm font-medium text-gray-700">New Image (Optional)</label>
                            <div class="mt-1">
                                <label for="update_image" class="upload-area flex flex-col items-center justify-center cursor-pointer">
                                    <input type="file" name="update_image" id="update_image" accept="image/jpg, image/jpeg, image/png" class="hidden">
                                    <div class="upload-preview">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-blue-500 mb-2"></i>
                                        <p class="text-gray-500">Click to change image</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" name="update_product" class="btn-primary flex-1">Update Product <i class="fas fa-save ml-2"></i></button>
                            <button type="button" onclick="document.getElementById('editModal').classList.remove('active')" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition duration-300">Cancel <i class="fas fa-times ml-2"></i></button>
                        </div>
                    </form>
        <?php
                }
            }
        }
        ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image upload preview
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const preview = this.closest('.upload-area').querySelector('.upload-preview');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="mt-2">`;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        // Product search functionality
        const productSearch = document.getElementById('product-search');
        if (productSearch) {
            productSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.product-card').forEach(card => {
                    const productName = card.getAttribute('data-name').toLowerCase();
                    if (productName.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Modal functionality
        const modal = document.getElementById('editModal');
        if (window.location.search.includes('update=')) {
            modal.classList.add('active');
        }

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.remove('active');
                setTimeout(() => {
                    window.location.href = 'admin_products.php';
                }, 300);
            });
        });

        // Prevent closing modal when clicking inside modal content
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                setTimeout(() => {
                    window.location.href = 'admin_products.php';
                }, 300);
            }
        });

        // Ensure modal content doesn't close modal
        modal.querySelector('.modal-content').addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
</script>

</body>

</html>