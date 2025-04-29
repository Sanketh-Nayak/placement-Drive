<?php
// Database connection
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "admin_db");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}



// Create upload directory if it doesn't exist
$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Function to handle file upload
function handleFileUpload($file, $prefix) {
    global $upload_dir;
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = $prefix . '_' . uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return $target_path;
        }
    }
    return null;
}

// Handle file uploads
$aadhar_path = handleFileUpload($_FILES['aadhar'], 'aadhar');
$pan_path = handleFileUpload($_FILES['pan'], 'pan');
$photo_path = handleFileUpload($_FILES['photo'], 'photo');
$signature_path = handleFileUpload($_FILES['signature'], 'signature');
$resume_path = handleFileUpload($_FILES['resume'], 'resume');
$certificate_path = isset($_FILES['certificate']) ? handleFileUpload($_FILES['certificate'], 'certificate') : null;

// Prepare SQL statement
$sql = "INSERT INTO student_registration (
    first_name, middle_name, last_name, aadhar_number, dob, gender, email, contact_number,
    house_number, area_village, city, district, state, pin_code, landmark,
    college_name, course_name, year_of_passout, cgpa, sslc_marks, puc_marks,
    has_experience, internships, company_name,
    aadhar_path, pan_path, photo_path, signature_path, resume_path, certificate_path
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssssssssssssssssssssss",
    $_POST['first_name'],
    $_POST['middle_name'],
    $_POST['last_name'],
    $_POST['aadhar_number'],
    $_POST['dob'],
    $_POST['gender'],
    $_POST['email'],
    $_POST['contact_number'],
    $_POST['house_number'],
    $_POST['area_village'],
    $_POST['city'],
    $_POST['district'],
    $_POST['state'],
    $_POST['pin_code'],
    $_POST['landmark'],
    $_POST['college_name'],
    $_POST['course_name'],
    $_POST['year_of_passout'],
    $_POST['cgpa'],
    $_POST['sslc_marks'],
    $_POST['puc_marks'],
    $_POST['has_experience'],
    $_POST['internships'],
    $_POST['company_name'],
    $aadhar_path,
    $pan_path,
    $photo_path,
    $signature_path,
    $resume_path,
    $certificate_path
);


if ($stmt->execute()) {
   $registration_id  = $conn->insert_id;
    echo json_encode(['success' => true, 'registration_id' => $registration_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving data: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?> 