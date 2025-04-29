<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected stream and message
    $stream = $_POST['options'];
    $message = $_POST['message'];

    // Prevent SQL injection
    $stream = $conn->real_escape_string($stream);
    $message = $conn->real_escape_string($message);

    // Prepare SQL query to insert data into announcements table
    $sql = "INSERT INTO announcements (stream, message) VALUES ('$stream', '$message')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message Has Been Sent!');</script>";
        echo "<script>window.location.href='http://localhost/Placement-/Admin/home.php';</script>";
    } else {
        echo "<script>alert('An Error Occured!');</script>";
    }

    // Close the database connection
    $conn->close();
}
?>
