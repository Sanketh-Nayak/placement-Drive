<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!--=============== REMIXICONS ===============-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
   <!--=============== CSS ===============-->
   <link rel="stylesheet" href="admin.css">
   <title>Placement Drive</title>
</head>
<body>
   <div class="login">
      <img src="bg.jpg" alt="image" class="login__bg">

      <form action="" class="login__form" method="POST">
         <h1 class="login__title">Admin</h1>
         <div class="login__inputs">
            <div class="login__box">
               <input type="email" placeholder="Email ID" required class="login__input" name="email" id="email">
               <i class="ri-mail-fill"></i>
            </div>
            <div class="login__box">
               <input type="password" placeholder="Password" required class="login__input" name="password" id="password">
               <i class="ri-lock-2-fill"></i>
            </div>
         </div>
         <div class="login__check">
            <div class="login__check-box">
               <input type="checkbox" class="login__check-input" id="user-check">
               <label for="user-check" class="login__check-label">Remember me</label>
            </div>
            <a href="adminforgot.php" class="login__forgot">Forgot Password?</a>
         </div>
         <button class="login__button" name="submit">Login</button>
      </form>
   </div>
   <?php
// Database connection
$conn = new mysqli("localhost", "root", "", "admin_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check credentials
    $sql = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
   $row = $result->fetch_assoc();
    if ($result->num_rows) {
         $_SESSION['email']= $email;
         $_SESSION['name'] = $row['name'];
        header("Location: ../home.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}
$conn->close();
?>
</body>
</html>
