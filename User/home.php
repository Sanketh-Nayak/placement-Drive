<?php 
session_start();
if(!isset($_SESSION['Email'])){
    session_destroy();
    header("Location:Login/student.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "admin_db");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}
$email = $_SESSION['Email'];
$sql = "SELECT email FROM student_registration WHERE email = '$email'";
$rs = mysqli_query($conn,$sql);
$rc = mysqli_num_rows($rs);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        #editProfile{
            text-decoration: none;
            color: black;
            margin-left: 10px;
            padding: 1px;
            font-size: larger;
        }
        #editProfile:hover {
            color: grey;
        }

    </style>
</head>

<body>
    <div class="header">
        <h2>🚀 Welcome to <span class="highlight">Placement Recruitment System</span> 🎯</h2>
    </div>
    <div class="area">
        <aside class="sidebar">
            <div class="profile">
                <img src="pages/profile.jpg" alt="Profile" class="profile-img"><br>
                <span class="profile-text"><?= $_SESSION['Username']; ?><a href="home.php?page=edit_profile" id="editProfile"><i class="ri-pencil-fill"></i></a></span>
                
            </div>
            <a href="home.php?page=dashboard" class="NavbarButton"><i class="ri-dashboard-line"></i>Dashboard</a>
            <?php if ($rc == 0): ?>
            <a href="home.php?page=register" class="NavbarButton"><i class="ri-user-add-line"></i> Registration</a>
            <?php endif; ?>
            <a href="home.php?page=CompanyList" class="NavbarButton"><i class="ri-building-line"></i> Company</a>
            <a href="home.php?page=materials" class="NavbarButton"><i class="ri-book-line"></i> Materials</a>
            <a href="home.php?page=Status" class="NavbarButton"><i class="ri-file-list-line"></i> Status</a>
            <a href="home.php?page=ActivityList" class="NavbarButton"><i class="fa-solid fa-chart-line"></i> Activity</a>
            <a href="home.php?page=Notification" class="NavbarButton"><i class="ri-notification-4-line"></i> Notification</a>
            <a href="home.php?page=feedback" class="NavbarButton"><i class="ri-send-plane-2-line"></i>Feedback</a>
            <a href="pages/logout.php" class="NavbarButton"><i class="ri-logout-box-line"></i> Log out</a>
        </aside>
        <div class="content">
            <div class="content_box" id="content">
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'Dashboard';
                $pageFile = __DIR__ . "/pages/{$page}.php";
                if (file_exists($pageFile)) {
                    include $pageFile;
                } else {
                    echo "<h2> Page Not Found 404 </h2>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>