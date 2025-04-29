<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "admin_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get registration ID from URL
$student = null;
$registration_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$email = $_GET['email'] ?? null;

if ($registration_id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM student_registration WHERE id = $registration_id");
    $student = mysqli_fetch_assoc($result);
} elseif ($email) {
    $email = mysqli_real_escape_string($conn, $email);
    $result = mysqli_query($conn, "SELECT * FROM student_registration WHERE email = '$email'");
    $student = mysqli_fetch_assoc($result);
}

if (!$student) {
    die("Registration not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Summary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .flex-container {
            display: flex;
        }

        .summary-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            flex-grow: 1;
        }

        #edit-button {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        #edit-button button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #edit-button button:hover {
            background-color: #0056b3;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .student-info {
            flex: 1;
        }

        .student-info h1 {
            color: #333;
            margin: 0 0 10px 0;
        }

        .student-info p {
            margin: 5px 0;
            color: #666;
        }

        .photo-section {
            width: 150px;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .photo-section img.photo {
            width: 120px;
            height: 120px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid #ddd;
        }

        .photo-section img.signature {
            width: 120px;
            height: 40px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
        }

        .photo-section .image-label {
            font-size: 12px;
            color: #666;
            margin-top: -5px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-item label {
            font-weight: bold;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }

        .info-item span {
            color: #333;
        }

        .documents-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }

        .document-list {
            list-style: none;
            padding: 0;
        }

        .document-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .document-list li i {
            margin-right: 10px;
            color: #28a745;
        }

        .document-list li a {
            color: #333;
            text-decoration: none;
        }

        .document-list li a:hover {
            text-decoration: underline;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #28a745;
            color: white;
        }

        .btn-primary:hover {
            background: #218838;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        @media print {
            .button-group {
                display: none;
            }
            body {
                background: white;
            }
            .summary-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="flex-container">
        <div class="summary-container">
            <div class="header">
                <div class="student-info">
                    <h1>Student Registration Summary</h1>
                    <p>Registration Date: <?php echo date('d/m/Y', strtotime($student['registration_date'])); ?></p>
                </div>
                <div class="photo-section">
                    <?php if($student['photo_path']): ?>
                        <img src="<?php echo htmlspecialchars($student['photo_path']); ?>" alt="Student Photo" class="photo">
                        <span class="image-label">Photo</span>
                    <?php else: ?>
                        <div class="photo" style="background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 40px; color: #ccc;"></i>
                        </div>
                        <span class="image-label">No Photo</span>
                    <?php endif; ?>
                    
                    <?php if($student['signature_path']): ?>
                        <img src="<?php echo htmlspecialchars($student['signature_path']); ?>" alt="Signature" class="signature">
                        <span class="image-label">Signature</span>
                    <?php else: ?>
                        <div class="signature" style="background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-signature" style="font-size: 20px; color: #ccc;"></i>
                        </div>
                        <span class="image-label">No Signature</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section">
                <h2>Personal Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Full Name</label>
                        <span><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Aadhar Number</label>
                        <span><?php echo htmlspecialchars($student['aadhar_number']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Date of Birth</label>
                        <span><?php echo date('d/m/Y', strtotime($student['dob'])); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Gender</label>
                        <span><?php echo htmlspecialchars($student['gender']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <span><?php echo htmlspecialchars($student['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Contact Number</label>
                        <span><?php echo htmlspecialchars($student['contact_number']); ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Address Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>House Number/Name</label>
                        <span><?php echo htmlspecialchars($student['house_number']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Area and Village</label>
                        <span><?php echo htmlspecialchars($student['area_village']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>City</label>
                        <span><?php echo htmlspecialchars($student['city']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>District</label>
                        <span><?php echo htmlspecialchars($student['district']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>State/Province</label>
                        <span><?php echo htmlspecialchars($student['state']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Pin Code</label>
                        <span><?php echo htmlspecialchars($student['pin_code']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Landmark</label>
                        <span><?php echo htmlspecialchars($student['landmark']); ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Academic Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>College Name</label>
                        <span><?php echo htmlspecialchars($student['college_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Course Name</label>
                        <span><?php echo htmlspecialchars($student['course_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Year of Passout</label>
                        <span><?php echo htmlspecialchars($student['year_of_passout']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>CGPA/Percentage</label>
                        <span><?php echo htmlspecialchars($student['cgpa']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>SSLC Marks</label>
                        <span><?php echo htmlspecialchars($student['sslc_marks']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>PUC Marks</label>
                        <span><?php echo htmlspecialchars($student['puc_marks']); ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Work Experience</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Has Work Experience</label>
                        <span><?php echo htmlspecialchars($student['has_experience']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Internships/Part-time Jobs</label>
                        <span><?php echo htmlspecialchars($student['internships']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Company Name & Duration</label>
                        <span><?php echo htmlspecialchars($student['company_name']); ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Documents</h2>
                <div class="documents-section">
                    <ul class="document-list">
                        <li>
                            <i class="fas fa-file-pdf"></i>
                            <a href="<?php echo htmlspecialchars($student['aadhar_path']); ?>" target="_blank">Aadhar Card</a>
                        </li>
                        <li>
                            <i class="fas fa-file-pdf"></i>
                            <a href="<?php echo htmlspecialchars($student['pan_path']); ?>" target="_blank">PAN Card</a>
                        </li>
                        <li>
                            <i class="fas fa-file-image"></i>
                            <a href="<?php echo htmlspecialchars($student['photo_path']); ?>" target="_blank">Student Photo</a>
                        </li>
                        <li>
                            <i class="fas fa-file-image"></i>
                            <a href="<?php echo htmlspecialchars($student['signature_path']); ?>" target="_blank">Signature</a>
                        </li>
                        <li>
                            <i class="fas fa-file-pdf"></i>
                            <a href="<?php echo htmlspecialchars($student['resume_path']); ?>" target="_blank">Resume</a>
                        </li>
                        <?php if ($student['certificate_path']): ?>
                        <li>
                            <i class="fas fa-file-pdf"></i>
                            <a href="<?php echo htmlspecialchars($student['certificate_path']); ?>" target="_blank">Experience Certificate</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="button-group">
                <button class="btn btn-primary" onclick="window.print()">Download PDF</button>
                <button class="btn btn-secondary" onclick="window.location.href='../home.php?page=dashboard'">Back to Dashboard</button>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?> 