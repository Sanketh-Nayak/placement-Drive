<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "admin_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle file upload
function handleFileUpload($file, $targetDir, $allowedTypes, $maxSize) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        echo "<script>alert('Invalid file type. Allowed types: " . implode(', ', $allowedTypes) . "');</script>";
        return null;
    }

    // Validate file size
    if ($file['size'] > $maxSize) {
        echo "<script>alert('File is too large. Maximum size: " . ($maxSize / 1024 / 1024) . "MB');</script>";
        return null;
    }

    // Generate unique filename
    $fileName = uniqid() . '.' . $fileType;
    $targetPath = $targetDir . $fileName;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $targetPath;
    }

    return null;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads first
    $uploadBasePath = '../uploads/';
    $photoPath = null;
    $signaturePath = null;
    $resumePath = null;

    // Handle photo upload
    if (isset($_FILES['photo'])) {
        $photoPath = handleFileUpload(
            $_FILES['photo'],
            $uploadBasePath . 'photos/',
            ['jpg', 'jpeg', 'png'],
            1 * 1024 * 1024 // 1MB
        );
    }

    // Handle signature upload
    if (isset($_FILES['signature'])) {
        $signaturePath = handleFileUpload(
            $_FILES['signature'],
            $uploadBasePath . 'signatures/',
            ['jpg', 'jpeg', 'png'],
            1 * 1024 * 1024 // 1MB
        );
    }

    // Handle resume upload
    if (isset($_FILES['resume'])) {
        $resumePath = handleFileUpload(
            $_FILES['resume'],
            $uploadBasePath . 'resumes/',
            ['pdf'],
            2 * 1024 * 1024 // 2MB
        );
    }

    // Collect form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $aadhar_number = $_POST['aadhar_number'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    $house_number = $_POST['house_number'];
    $area_village = $_POST['area_village'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $pin_code = $_POST['pin_code'];
    $landmark = $_POST['landmark'];

    $college_name = $_POST['college_name'];
    $course_name = $_POST['course_name'];
    $year_of_passout = $_POST['year_of_passout'];
    $cgpa = $_POST['cgpa'];
    $sslc_marks = $_POST['sslc_marks'];
    $puc_marks = $_POST['puc_marks'];

    $has_experience = $_POST['has_experience'];

    // Build the update query dynamically based on which files were uploaded
    $updateFields = [
        "first_name = ?", "middle_name = ?", "last_name = ?", "aadhar_number = ?",
        "dob = ?", "gender = ?", "contact_number = ?", "house_number = ?",
        "area_village = ?", "city = ?", "district = ?", "state = ?",
        "pin_code = ?", "landmark = ?", "college_name = ?", "course_name = ?",
        "year_of_passout = ?", "cgpa = ?", "sslc_marks = ?", "puc_marks = ?",
        "has_experience = ?"
    ];

    $params = [
        $first_name, $middle_name, $last_name, $aadhar_number, $dob, $gender,
        $contact_number, $house_number, $area_village, $city, $district,
        $state, $pin_code, $landmark, $college_name, $course_name, $year_of_passout,
        $cgpa, $sslc_marks, $puc_marks, $has_experience
    ];
    $types = str_repeat('s', 21);

    if ($photoPath) {
        $updateFields[] = "photo_path = ?";
        $params[] = $photoPath;
        $types .= 's';
    }
    if ($signaturePath) {
        $updateFields[] = "signature_path = ?";
        $params[] = $signaturePath;
        $types .= 's';
    }
    if ($resumePath) {
        $updateFields[] = "resume_path = ?";
        $params[] = $resumePath;
        $types .= 's';
    }

    $params[] = $email;
    $types .= 's';

    $query = "UPDATE student_registration SET " . implode(', ', $updateFields) . " WHERE email = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "<script>alert('Student Details Updated and Saved Successfully');</script>";
            echo "<script>window.location.href='http://localhost/Placement-/User/pages/summary.php?email=$email';</script>";
        } else {
            echo "<script>alert('An Error Occurred!');</script>";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
