<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "admin_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = ""; // Store email if found

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    // Check if email exists
    $check_sql = "SELECT email FROM admin WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        if (isset($_POST['update'])) {
            // Update password if email exists
            $new_password = trim($_POST['new_password']);
            $update_sql = "UPDATE admin SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ss", $new_password, $email);
            
            if ($stmt->execute()) {
                echo "<script>alert('Password changed successfully!'); window.location.href='admin.php';</script>";
            } else {
                echo "<script>alert('Error updating password! Try again.');</script>";
            }
        }
    } else {
        echo "<script>alert('Email ID not found!'); window.location.href='adminforgot.php';</script>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="adminforgot.css">

    <title>Admin - Forgot Password</title>
</head>
<body>
    <div class="login">
        <img src="bg.jpg" alt="image" class="login__bg">

        <form action="" class="login__form" method="POST">
            <h1 class="login__title">Forgot Password?</h1>

            <div class="login__inputs">
                <div class="login__box">
                    <input type="email" placeholder="Enter Registered Email ID" required class="login__input" name="email" value="<?php echo $email; ?>" required>
                    <i class="ri-mail-fill"></i>
                </div>
                
                <div class="login__box">
                    <input type="password" placeholder="Enter New Password" required class="login__input" name="new_password" required>
                    <i class="ri-lock-2-fill"></i>
                </div>
            </div>

            <button type="submit" class="login__button" name="update">Reset Password</button>
        </form>
    </div>
</body>
</html>
