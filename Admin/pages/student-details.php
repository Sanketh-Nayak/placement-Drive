<?php

include './db.php';

// Query to select all student details
$query = "SELECT * FROM student_registration ORDER BY id DESC";
$student_details = $conn->query($query);
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="http://localhost/Placement-/Admin/css/student-details.css">
    <style>
        .all-student-details {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ind-student-details {
            display: none;
        }

        .detail-section {
            background-color: rgba(26, 115, 232, 0.53);
            padding: 8px 10px;
            width: 80%;
            margin: 0 auto;
            margin-top: 40px;
            border: 1px solid rgb(0, 0, 0);
            font-family: Tahoma;
        }

        .view-details-btn,
        .back-button {
            width: auto;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        h1 {
            text-align: center;
        }

        hr {
            width: 80%;
            margin: 20px auto;
            background-color: black;
            box-shadow: 0 0 0px 1px black;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            color: black;
            margin: 5px auto;
            border: 1px solid #ddd;
        }

        th {
            padding: 12px;
            text-align: left;
            font-size: 14px;
            border: 1px solid #ddd;
            width: 40%;
            background-color: #d0d0d0;
            color: #333;
            border: 1px solid rgb(43, 43, 43);
        }

        td {
            background-color: #fafafa;
            border: 1px solid #838fff;
        }

        a {
            text-decoration: none;
        }

    </style>
</head>

<body>
    <main class="main-content">
        <!-- All students list -->
        <?php if($student_details->num_rows > 0) { ?>
<div class="all-student-details">
            <?php while ($row = $student_details->fetch_assoc()) { ?>
    <div class="student-card">
        <div class="student-info">
                        <p id="student-name"><?php echo ucwords($row['first_name']." ".$row['last_name']); ?></p>
                        <p id="student-email"><?php echo htmlspecialchars($row['email']); ?></p>
        </div>
                    <button class="view-details-btn" onclick="showStudentDetails('<?php echo htmlspecialchars($row['email']); ?>')">View Details</button>
    </div>
            <?php } ?>
        </div>
        <?php } else { ?>
            <h2>No Registered Students Found!</h2>
        <?php } ?>

        <!-- Individual Student Details Sections -->
<div class="ind-student-details">
            <button class="back-button" onclick="showAllStudents()">Back</button>
            <h1 id="heading-student-name"></h1>
    <hr>
    <h3 class="detail-section">Personal Information</h3>
    <table>
                <tr id="det-fname"><th>First Name</th>
                    <td></td>
                </tr>
                <tr id="det-mname"><th>Middle Name</th>
                    <td></td>
                </tr>
                <tr id="det-lname"><th>Last Name</th>
                    <td></td>
                </tr>
                <tr id="det-email"><th>Email</th>
                    <td></td>
                </tr>
                <tr id="det-phone"><th>Phone</th>
                    <td></td>
                </tr>
                <tr id="det-dob"><th>DOB</th>
                    <td></td>
                </tr>
                <tr id="det-gender"><th>Gender</th>
                    <td></td>
                </tr>
                <tr id="det-adhaar"><th>Adhaar Number</th>
                    <td></td>
                </tr>
    </table>
    <h3 class="detail-section">Address Information</h3>
            <table>
                <tr id="det-address"><th>House Number / Name</th>
                    <td></td>
                </tr>
                <tr id="det-village"><th>Village / Area</th>
                    <td></td>
                </tr>
                <tr id="det-city"><th>City</th>
                    <td></td>
                </tr>
                <tr id="det-lmark"><th>Landmark</th>
                    <td></td>
                </tr>
                <tr id="det-district"><th>District</th>
                    <td></td>
                </tr>
                <tr id="det-pincode"><th>Pincode</th>
                    <td></td>
                </tr>
                <tr id="det-state"><th>State</th>
                    <td></td>
                </tr>
            </table>
            <h3 class="detail-section">Academic Information</h3>
            <table>
                <tr id="det-college"><th>College Name</th>
                    <td></td>
                </tr>
                <tr id="det-course"><th>Course Name</th>
                    <td></td>
                </tr>
                <tr id="det-yrpassing"><th>Year Of Passing</th>
                    <td></td>
                </tr>
                <tr id="det-cgpa"><th>CGPA</th>
                    <td></td>
                </tr>
                <tr id="det-sslc"><th>SSLC Marks Percentage</th>
                    <td></td>
                </tr>
                <tr id="det-puc"><th>PUC Marks Percentage</th>
                    <td></td>
                </tr>
            </table>
            <h3 class="detail-section">Student Documents</h3>
    <table>
                <tr id="det-aadhar"><th>Adhaar Card</th>
                    <td></td>
                </tr>
                <tr id="det-pan"><th>PAN Card</th>
                    <td></td>
                </tr>
                <tr id="det-photo"><th>Photo</th>
                    <td></td>
                </tr>
                <tr id="det-signature"><th>Signature</th>
                    <td></td>
                </tr>
                <tr id="det-resume"><th>Resume</th>
                    <td></td>
                </tr>
                <tr id="det-certi"><th>Certificates</th>
                    <td></td>
                </tr>
    </table>
</div>

<script>
    const allStudentDetails = document.querySelector('.all-student-details');
    const indStudentDetails = document.querySelector('.ind-student-details');

                function showStudentDetails(student_email) {
                    // Hide all student details and show Individual details
            allStudentDetails.style.display = 'none';
            indStudentDetails.style.display = 'block';

                    // Get Student Name and Email
                    let student_name = document.getElementById('student-name').innerText;

                    document.getElementById('heading-student-name').innerText = student_name;

                    fetch('http://localhost/Placement-/Admin/backend/get_student_details.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'email=' + encodeURIComponent(student_email)  // Pass email to the PHP script
                    })
                    .then(response => response.json())  // Parse the response as JSON
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            const fields = [
                                { id: 'det-fname', dataField: 'first_name' },
                                { id: 'det-mname', dataField: 'middle_name', default: 'N/A' },
                                { id: 'det-lname', dataField: 'last_name' },
                                { id: 'det-email', dataField: 'email' },
                                { id: 'det-phone', dataField: 'contact_number' },
                                { id: 'det-dob', dataField: 'dob' },
                                { id: 'det-gender', dataField: 'gender' },
                                { id: 'det-adhaar', dataField: 'aadhar_number' },
                                { id: 'det-address', dataField: 'house_number' },
                                { id: 'det-village', dataField: 'area_village' },
                                { id: 'det-city', dataField: 'city' },
                                { id: 'det-lmark', dataField: 'landmark' },
                                { id: 'det-district', dataField: 'district' },
                                { id: 'det-pincode', dataField: 'pin_code' },
                                { id: 'det-state', dataField: 'state' },
                                { id: 'det-college', dataField: 'college_name' },
                                { id: 'det-course', dataField: 'course_name' },
                                { id: 'det-yrpassing', dataField: 'year_of_passout' },
                                { id: 'det-cgpa', dataField: 'cgpa' },
                                { id: 'det-sslc', dataField: 'sslc_marks' },
                                { id: 'det-puc', dataField: 'puc_marks' }
                            ];
                            fields.forEach(field => {
                                let value = data[field.dataField] !== undefined ? data[field.dataField] : field.default;
                                document.getElementById(field.id).querySelector('td').innerText = value;
                            });

                            let createLink = (path, elementId) => {
                                let td = document.getElementById(elementId).querySelector('td');
                                td.innerHTML = '';
                                let link = document.createElement('a');
                                link.textContent = 'View File';
                                link.target = '_blank';
                                link.href = "http://localhost/Placement-/User/Dashboard/" + path;
                                td.appendChild(link);
                            };
                            createLink(data.aadhar_path, 'det-aadhar');
                            createLink(data.pan_path, 'det-pan');
                            createLink(data.photo_path, 'det-photo');
                            createLink(data.signature_path, 'det-signature');
                            createLink(data.resume_path, 'det-resume');
                            createLink(data.certificate_path, 'det-certi');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching student details:', error);
                    });
                }

                function showAllStudents() {
                allStudentDetails.style.display = 'flex';
                indStudentDetails.style.display = 'none';
                }
</script>
    </main>
</body>

</html>