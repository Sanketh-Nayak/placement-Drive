<?php
// get_student_details.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];  // Get the email from the POST request

    // Connect to the database
    include './db.php';

    // Prepare the query to get student details based on email
    $query = "SELECT * FROM student_registration WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);  // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the student details
    if ($result->num_rows > 0) {
        $student_details = $result->fetch_assoc();
        // Return the details as a JSON response
        echo json_encode($student_details);
    } else {
        echo json_encode(["error" => "Student not found"]);
    }

    // Close the connection
    $conn->close();
}
?>
