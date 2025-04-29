<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "admin_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = isset($_SESSION['Email']) ? $_SESSION['Email'] : null;
echo "<script>alert($email);</script>";
$row = null;
if($email) {
    $sql = "SELECT * FROM student_registration WHERE email = '$email'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}

if (!$row) {
    die("Registration not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form with Document Upload</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            height: 100vh;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        .reg_container {
            font-family: 'Arial', sans-serif;
            height: calc(100vh - 60px);
            display: flex;
            justify-content: center;
        }

        .registration-form {
            box-sizing: border-box;
            width: 100%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            overflow: auto;
            border: 25px solid white;
            margin: 15px 10px;
        }

        .section {
            background:rgb(250, 250, 250);
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 5px;
            border: 2px solid #eaeaea;
        }

        .section h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
            font-size: 18px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section label {
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 14px;
        }

        .section input, .section select {
            width: 100%;
            padding: 10px;
            margin: 4px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .section input:focus, .section select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        .save, .cancel {
            padding: 10px 20px;
            color: white;
            font-weight: bold;
            font-size: large;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.3s ease;
        }

        .save {
            background: #007bff;
        }
        .cancel {
            background: #ff0000;
        }

        .save:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }
        .cancel:hover {
            background: #ff0033;
            transform: translateY(-1px);
        }

        .upload-section {
            background: rgb(250, 250, 250);
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #eaeaea;
            margin-top: 12px;
        }

        .upload-section h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
            font-size: 18px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .file-input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .file-input-container label {
            display: block;
            margin: 15px 0 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 14px;
        }

        .file-input-container input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .file-input-container input[type="file"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        .current-file {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
            padding: 8px;
            background: rgba(0,0,0,0.03);
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .current-file i {
            color: #007bff;
        }

        .file-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
            font-style: italic;
        }

        .error-message, .success-message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            animation: fadeIn 0.3s ease;
        }

        .error-message {
            background-color: #fff5f5;
            color: #dc3545;
            border: 1px solid #ffebeb;
        }

        .success-message {
            background-color: #f0fff4;
            color: #28a745;
            border: 1px solid #dcffe4;
        }

        #experienceDetails {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
        }

        tr {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        a {
            text-decoration: none
        }

        .flex {
            display: flex;
            justify-content: center;
            gap: 1em;
        }
    </style>
</head>
<body>
<div class="reg_container">
    <form class="registration-form" id="registrationForm" method="POST" action="http://localhost/Placement-/User/backend/update_student_details.php" enctype="multipart/form-data">
        
        <!-- Personal Information Section -->
        <div class="section active" id="personalInfo">
            <h3>1. Personal Information</h3>
            <table>
                <tr>
                    <td><label for="firstName">First Name</label></td>
                    <td><input type="text" id="firstName" name="first_name" value="<?= $row['first_name'] ?>" required></td>
                </tr>
                <tr>
                    <td><label for="middleName">Middle Name</label></td>
                    <td><input type="text" id="middleName" name="middle_name" value="<?= $row['middle_name'] ?>"></td>
                </tr>
                <tr>
                    <td><label for="lastName">Last Name</label></td>
                    <td><input type="text" id="lastName" name="last_name" value="<?= $row['last_name'] ?>" required></td>
                </tr>
                <tr>
                    <td><label for="aadharNumber">Aadhar Number</label></td>
                    <td><input type="text" id="aadharNumber" name="aadhar_number" value="<?= $row['aadhar_number'] ?>" required></td>
                </tr>
                <tr>
                    <td><label for="dob">Date of Birth</label></td>
                    <td><input type="date" id="dob" name="dob" value="<?= $row['dob'] ?>" required></td>
                </tr>
                <tr>
                    <td><label for="gender">Gender</label></td>
                    <td>
                        <select id="gender" name="gender" required>
                            <option value="<?= $row['gender'] ?>"><?= $row['gender'] ?></option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="email">Email</label></td>
                    <td><input type="email" id="email" name="email" value="<?= $row['email'] ?>" readonly></td>
                </tr>
                <tr>
                    <td><label for="contactNumber">Contact Number</label></td>
                    <td><input type="tel" id="contactNumber" name="contact_number" value="<?= $row['contact_number'] ?>" inputmode="numeric" required></td>
                </tr>
            </table>
        </div>

        <!-- Address Section -->
        <div class="section" id="addressSection">
            <h3>2. Address Details</h3>
            <table>
                <tr>
                    <td><label>House Number/Name</label></td>
                    <td><input type="text" id="houseNumber" name="house_number" value="<?= $row['house_number'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>Area and Village</label></td>
                    <td><input type="text" id="areaVillage" name="area_village" value="<?= $row['area_village'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>City Name</label></td>
                    <td><input type="text" id="city" name="city" value="<?= $row['city'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>District</label></td>
                    <td><input type="text" id="district" name="district" value="<?= $row['district'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>State/Province</label></td>
                    <td><input type="text" id="state" name="state" value="<?= $row['state'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>Pin Code</label></td>
                    <td><input type="text" id="pinCode" name="pin_code" value="<?= $row['pin_code'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>Landmark/Nearest Location</label></td>
                    <td><input type="text" id="landmark" name="landmark" value="<?= $row['landmark'] ?>"></td>
                </tr>
            </table>
        </div>

        <!-- Academic Details Section -->
        <div class="section" id="academicDetails">
            <h3>3. Academic Details</h3>
            <table>
                <tr>
                    <td><label>College Name</label></td>
                    <td><input type="text" id="collegeName" name="college_name" value="<?= $row['college_name'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>Course Name</label></td>
                    <td><input type="text" id="courseName" name="course_name" value="<?= $row['course_name'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>Year of Passout</label></td>
                    <td><input type="text" id="yearOfPassout" name="year_of_passout" value="<?= $row['year_of_passout'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>CGPA/Percentage</label></td>
                    <td><input type="text" id="cgpa" name="cgpa" value="<?= $row['cgpa'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>SSLC Marks</label></td>
                    <td><input type="text" id="sslcMarks" name="sslc_marks" value="<?= $row['sslc_marks'] ?>" required></td>
                </tr>
                <tr>
                    <td><label>PUC Marks</label></td>
                    <td><input type="text" id="pucMarks" name="puc_marks" value="<?= $row['puc_marks'] ?>" required></td>
                </tr>
            </table>
        </div>

        <!-- Work Experience Section -->
        <div class="section" id="workExperience">
            <h3>4. Work Experience</h3>
            <table>
                <tr>
                    <td><label>Do you have any work experience?</label></td>
                    <td>
                        <select id="experienceSelect" name="has_experience">
                            <option value="<?= $row['has_experience'] ?>"><?= $row['has_experience'] ?></option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Document Upload Section -->
        <div class="section" id="documentUpload">
            <h3>5. Document Upload</h3>
            <div class="file-input-container">
                <label for="photo">Profile Photo</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png" 
                    onchange="validateFileSize(this, 1)">
                <?php if($row['photo_path']): ?>
                    <div class="current-file">
                        <i class="fas fa-image"></i>
                        <span>Current: <?= basename($row['photo_path']) ?></span>
                        <img src="<?= $row['photo_path'] ?>" alt="Current Photo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-left: auto;">
                    </div>
                <?php endif; ?>
                <div class="file-requirements">Maximum file size: 1MB | Accepted formats: JPG, PNG</div>
            </div>

            <div class="file-input-container">
                <label for="resume">Resume (PDF)</label>
                <input type="file" id="resume" name="resume" accept=".pdf" 
                    onchange="validateFileSize(this, 2)">
                <?php if($row['resume_path']): ?>
                    <div class="current-file">
                        <i class="fas fa-file-pdf"></i>
                        <span>Current: <?= basename($row['resume_path']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="file-requirements">Maximum file size: 2MB | Accepted format: PDF</div>
            </div>
        </div>

        <script>
        function validateFileSize(input, maxSizeMB) {
            const maxSize = maxSizeMB * 1024 * 1024; // Convert to bytes
            if (input.files[0] && input.files[0].size > maxSize) {
                alert(`File size must be less than ${maxSizeMB}MB`);
                input.value = '';
                return;
            }

            // Preview image if it's a photo
            if (input.id === 'photo') {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = input.parentElement.querySelector('img');
                        if (!preview) {
                            const newPreview = document.createElement('img');
                            newPreview.style.cssText = 'width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-left: auto;';
                            input.parentElement.appendChild(newPreview);
                            newPreview.src = e.target.result;
                        } else {
                            preview.src = e.target.result;
                        }
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
        </script>

        <div class="flex">
            <a href="http://localhost/Placement-/User/home.php"><button type="button" class="cancel">Cancel</button></a>
            <button type="submit" class="save">Save Changes</button>
        </div>
    </form>
</div>
</body>
</html>