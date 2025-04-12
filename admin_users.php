<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Animate.css CDN for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --primary-color: #1e3a8a; /* Deep blue */
            --secondary-color: #3b82f6; /* Bright blue */
            --accent-color: #10b981; /* Green for success */
            --danger-color: #ef4444; /* Red for delete */
            --background-color: #f1f5f9; /* Light slate background */
            --card-bg: #ffffff; /* White card background */
            --text-color: #1e293b; /* Dark slate text */
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: var(--background-color);
            font-family: 'Inter', sans-serif;
            margin-top: 80px; /* Adjust for fixed header */
            color: var(--text-color);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: var(--shadow);
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 50;
        }

        .card {
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .user-card {
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: slideIn 0.5s ease-out;
            position: relative;
            border-left: 4px solid var(--secondary-color);
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .user-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.75rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .user-card:hover .user-actions {
            opacity: 1;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .delete-btn {
            background: var(--danger-color);
        }

        .delete-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .search-box {
            position: relative;
            width: 350px;
            margin-bottom: 2rem;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .search-box input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .empty {
            text-align: center;
            padding: 3rem;
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }

        .user-type-admin {
            color: #f97316; /* Orange for admin */
            font-weight: 600;
        }

        .user-type-user {
            color: #64748b; /* Gray for regular user */
            font-weight: 500;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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
    <!-- Add Google Fonts for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

<?php include 'admin_header.php'; ?>

<div class="container mx-auto mt-16">
    <div class="card animate__animated animate__fadeIn">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">User Accounts</h1>
            <div class="search-box">
                <input type="text" placeholder="Search users..." id="user-search" class="w-full">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <div class="user-grid">
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            if (mysqli_num_rows($select_users) > 0) {
                while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            ?>
                    <div class="user-card" data-name="<?php echo strtolower($fetch_users['name']); ?>">
                        <div class="user-actions">
                            <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Delete this user?');" title="Delete User">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-semibold text-gray-700">User ID:</span> <span class="text-gray-600"><?php echo $fetch_users['id']; ?></span></p>
                            <p class="text-sm"><span class="font-semibold text-gray-700">Username:</span> <span class="text-gray-600"><?php echo $fetch_users['name']; ?></span></p>
                            <p class="text-sm"><span class="font-semibold text-gray-700">Email:</span> <span class="text-gray-600"><?php echo $fetch_users['email']; ?></span></p>
                            <p class="text-sm"><span class="font-semibold text-gray-700">User Type:</span> 
                                <span class="<?php echo $fetch_users['user_type'] == 'admin' ? 'user-type-admin' : 'user-type-user'; ?>">
                                    <?php echo $fetch_users['user_type']; ?>
                                </span>
                            </p>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="empty"><i class="fas fa-users text-5xl text-gray-400 mb-4"></i><p class="text-gray-600 text-lg font-medium">No user accounts found!</p></div>';
            }
            ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User search functionality
        const userSearch = document.getElementById('user-search');
        if (userSearch) {
            userSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.user-card').forEach(card => {
                    const userName = card.getAttribute('data-name').toLowerCase();
                    if (userName.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Add animation delay to user cards for staggered effect
        document.querySelectorAll('.user-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>

</body>

</html>