<?php
session_start();
// Only run when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Create connection
   $conn = new mysqli("localhost", "root", "", "admin_db");
   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   $email = $_POST['email'];
   $password = $_POST['password'];

   $select_sql = "SELECT * FROM regd_studs WHERE email='$email'";
   $result = $conn->query($select_sql);
   
   // If Email ID Does Not Exist
   if($result->num_rows <= 0)
   {
     echo "<script>alert('No Student Found Registered With That Email ID');</script>";
   }
   // If Email Id Exists
   else 
   {
      $row = $result->fetch_assoc();
      if ($password == $row['password'])
      {
         $_SESSION['Username'] = $row['fullname'];
         echo "<script>
                  alert('Student Login Successfull!');
                  window.location.href = 'http://localhost/Placement-/User/home.php';
               </script>";
         $_SESSION['Email']=$email;
         header("Location:../home.php");
         }
      else
      {
         echo "<script>
                  alert('Password Is Wrong');
               </script>";
      }

   }
}
?>

<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== REMIXICONS ===============-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="student.css">

      <title>Placement Drive</title>
   </head>
   <body>
      <div class="login">
         <img src="Student.jpg" alt="image" class="login__bg">

         <form action="" method="POST" class="login__form">
            <h1 class="login__title">Student</h1>

            <div class="login__inputs">
               <div class="login__box">
                  <input type="email" name="email" placeholder="Email ID" required class="login__input" id="email">
                  <i class="ri-mail-fill"></i>
               </div>

               <div class="login__box">
                  <input type="password" name="password" placeholder="Password" required class="login__input" id="password">
                  <i class="ri-lock-2-fill"></i>
               </div>
            </div>

            <div class="login__check">
               <div class="login__check-box">
                  <input type="checkbox" class="login__check-input" id="user-check">
                  <label for="user-check" class="login__check-label">Remember me</label>
               </div>

               <a href="studentforgot.php" class="login__forgot">Forgot Password?</a>
            </div>

            <button type="submit" class="login__button">Login</button>

            <div class="login__register">
               Don't have an account? <a href="studentregister.php">Register</a>
            </div>
         </form>
      </div>
   </body>
   <script src=""></script>
</html>


<!-- Form Submission (Student Login) Backend Code -->

