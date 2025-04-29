<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
  <link rel="stylesheet" href="studentforgot.css">
  <title>Placement Drive</title>

  <style>
    #resetPasswordForm:hover, #resetPasswordForm input:hover {
      cursor: not-allowed;
    }
    .disabled-button:hover {
      background-color: white;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <div class="login">
    <img src="Student.jpg" alt="image" class="login__bg">

    <!-- First Box: Forgot Password -->
    <form action="" method="POST" class="login__form" id="forgotPasswordForm">
      <h1 class="login__title">Forgot Password?</h1>
      <div class="login__inputs">
        <div class="login__box">
          <input type="email" name="email" value="<?php $email ?>" placeholder="Registered Email ID" required class="login__input" id="email" autofocus>
          <i class="ri-mail-fill"></i>
        </div>
      </div>
      <button type="submit" class="login__button" id="verifyButton">Verify</button>
    </form>

    <!-- Second Box: Reset Password (initially disabled) -->
    <form action="" method="POST" class="login__form" id="resetPasswordForm">
      <h1 class="login__title">Reset Password</h1>
      <div class="login__inputs">
        <div class="login__box">
          <input type="password" name="password" placeholder="New Password" required class="login__input" id="password" disabled>
          <i class="ri-lock-2-fill"></i>
        </div>
        <div class="login__box">
          <input type="password" name="cpassword" placeholder="Confirm Password" required class="login__input" id="confirmPassword" disabled>
          <i class="ri-lock-2-fill"></i>
        </div>
      </div>
      <button type="submit" class="login__button disabled-button" id="resetButton" disabled>Reset</button>
    </form>
  </div>
</body>
</html>



<!-- Form Submission (Email Verification For Forgot Password) Backend Code -->

<?php
// Create connection
$conn = new mysqli('localhost', "root", "", "admin_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = ''; // Initialize email to an empty string

// Check if email is posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    
  if(isset($_POST['password']) and isset($_POST['cpassword']))
  {
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if($password == $cpassword)
    {
      $update_sql = "UPDATE regd_studs SET password='$password'";
      $result = $conn->query($update_sql);
      echo "<script>
              alert('Password Changed Successfully!');
            </script>";
    }
    else
    {
      echo "<script>
              alert('Password Did Not Match');
            </script>";
    }
  } 

  else
  {
    // Query to check if email exists in the database
    $select_sql = "SELECT email FROM regd_studs WHERE email='$email'";
    $result = $conn->query($select_sql);

    // If email exists, show an alert and enable the second form
    if ($result->num_rows > 0) {
        echo "<script>
                alert('Email verified successfully. Please reset your password.');
                document.getElementById('password').disabled = false;
                document.getElementById('confirmPassword').disabled = false;
                document.getElementById('resetButton').disabled = false;
                document.getElementById('resetButton').classList.remove('disabled-button');
                
                // Pre-fill the email input with the verified email
                document.getElementById('email').value = '" . htmlspecialchars($email) . "';
                
                // Remove the style block for disabling hover effects
                var styleElement = document.querySelector('style');
                if (styleElement) {
                    styleElement.remove();
                }
              </script>";
    } else {
        echo "<script>
                alert('No Student Found Registered With That Email ID');
                document.getElementById('password').disabled = true;
                document.getElementById('confirmPassword').disabled = true;
                document.getElementById('resetButton').disabled = true;
                document.getElementById('resetButton').classList.add('disabled-button');
              </script>";
    }
  }
}
?>


