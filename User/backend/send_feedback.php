<?php
session_start();
// Create connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected stream and message
    $email = $_SESSION['Email'];

    $select = "SELECT * FROM regd_studs WHERE email='$email'";
    $response = mysqli_query($conn, $select);

    if($response)
    {
        $student = $response->fetch_assoc();
        $name = $student['fullname'];
        $stream = $student['stream'];
        $message = $_POST['message'];

        $sql = "INSERT INTO feedbacks (name, email, stream, message) VALUES ('$name', '$email', '$stream', '$message')";
    
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Message Has Been Sent!');</script>";
            echo "<script>window.location.href='http://localhost/Placement-/User/home.php?page=dashboard';</script>";
        } else {
            echo "<script>alert('An Error Occured!');</script>";
        }

    }
    // Close the database connection
    $conn->close();
}
?>
