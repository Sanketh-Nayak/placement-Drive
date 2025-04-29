<?php
 session_start();
 if(!isset($_SESSION['email'])){
    session_destroy();
    header("Location:Login/admin.php");
    exit();
 }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/home1.css">
    <link rel="stylesheet" href="css/addCompany.css">
    <link rel="stylesheet" href="css/profile-dropdown.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- <div class="logo">
                <h2>ADMIN</h2>
                <span class="online-status"></span>
            </div> -->
            <div class="admin-info">
                <div class="admin-avatar">
                    <i class="fas fa-user-circle"></i>
                    <span><?= $_SESSION['name']?></span>
                </div>
                <span class="status">Online</span>
            </div>
            <nav class="menu">
                <a href="home.php?page=dashboard" class="menu-item"><i class="fas fa-th-large"></i>Dashboard</a>
                <a href="home.php?page=addCompany" class="menu-item"><i class="fas fa-building"></i>Company Registration</a>
                <a href="home.php?page=student-details" class="menu-item"><i class="fa-solid fa-user"></i>Student Details</a>
                <a href="home.php?page=student-activity" class="menu-item"><i class="fa-solid fa-chart-line"></i>Activity</a>
                <a href="home.php?page=materials" class="menu-item"><i class="fa-solid fa-folder"></i>Materials</a>
                <a href="home.php?page=reports" class="menu-item"><i class="fa-solid fa-file-lines"></i>Report & Feedback</a>
                <a href="pages/logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <!-- Add Profile Dropdown -->
            <div class="profile-container">
                <div class="profile-dropdown">
                    <div class="profile-info">
                        <h4><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin User'; ?></h4>
                        <p><?php echo isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'admin@example.com'; ?></p>
                    </div>
                </div>
            </div>

            <div class="content_box" id="content" style="overflow: auto; scrollbar-color: rgba(0, 0, 0, 0.173) rgba(0, 0, 0, 0.173);">
                <?php
                // Load default page or dynamic content
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                $pageFile = __DIR__ . "/pages/{$page}.php";

                if (file_exists($pageFile)) {
                    include $pageFile;
                } else {
                    echo "<h2>Page Not Found</h2>";
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>