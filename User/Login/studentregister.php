<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css" crossorigin="">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="studentregister.css">

    <title>Placement Drive</title>
</head>

<body>
    <div class="login">
        <img src="Student.jpg" alt="image" class="login__bg">

        <form action="" class="login__form" method="POST">
            <h1 class="login__title">Register</h1>

            <div class="login__inputs">
                <div class="login__box">
                    <input type="text" name="fullname" placeholder="Full Name" required class="login__input">
                    <i class="ri-user-fill"></i>
                </div>

                <div class="login__box">
                    <input type="email" name="email" placeholder="Email ID" required class="login__input">
                    <i class="ri-mail-fill"></i>
                </div>

                <div class="login__box">
                    <input type="password" name="password" placeholder="Password" required class="login__input">
                    <i class="ri-lock-2-fill"></i>
                </div>

                <select name="stream" required class="login__input login__select ">
                    <option value="" disabled selected>Select Stream</option>
                    <option value="BCOM">BCOM</option>
                    <option value="BBA">BBA</option>
                    <option value="BCA">BCA</option>
                    <option value="BA">BA</option>
                </select>
            </div>

            <div class="login__check">
                <div class="login__check-box">
                    <input type="checkbox" class="login__check-input" id="user-check">
                    <label for="user-check" class="login__check-label">Remember me</label>
                </div>
            </div>

            <button type="submit" class="login__button">Register</button>
        </form>
    </div>
</body>

</html>

<?php
// Only run when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    include './db.php';

    // Get form data
    $full_name = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stream = $_POST['stream'];
    $approval = "pending";

    // Check if the email already exists in reg_hist
    $select_sql = "SELECT email FROM reg_hist WHERE email='$email'";
    $result = $conn->query($select_sql);
    if ($result->num_rows > 0) {
        echo "<script>alert('User with same email already exists!');</script>";
    } else {
        // Insert into database
        $insert_sql = "INSERT INTO reg_hist (fullname, email, password, stream, approval) VALUES ('$full_name', '$email', '$password', '$stream', '$approval')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "<script>
                    alert('Registration Request Has Been Sent');
                    window.location.href = 'student.php';
                 </script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
        $conn->close();
    }
}
?>