<!-- Form Submission (Registration Request Accept/Reject) Backend Code -->

<?php

// Create connection
$conn = new mysqli('localhost', "root", "", "admin_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approval action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["approval-action"])) {
    $email = $conn->real_escape_string($_POST["email"]);
    $action = ($_POST["approval-action"] === "accept") ? "accepted" : "rejected";

    $update_sql = "UPDATE reg_hist SET approval='$action' WHERE email='$email'";
    $result = $conn->query($update_sql);

    $select_sql = "SELECT fullname, email, password, stream FROM reg_hist WHERE email='$email'";
    $result = $conn->query($select_sql);
    $conn->close();

    $row = $result->fetch_assoc();
    $full_name = $row['fullname'];
    $email = $row['email'];
    $password = $row['password'];
    $stream = $row['stream'];

    if($action == "accepted") {
        $conn = new mysqli('localhost', "root", "", "admin_db");
        $insert_sql = "INSERT INTO regd_studs (fullname, email, password, stream, date) VALUES ('$full_name', '$email', '$password', '$stream', 'SomeDateHere')";
        $result = $conn->query($insert_sql);
        $conn->close();
    }

    echo "<script>
            window.location.href = 'http://localhost/Placement-/Admin/home.php?page=notifications';
        </script>";
}
?>