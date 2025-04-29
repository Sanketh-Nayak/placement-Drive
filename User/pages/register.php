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
            overflow: hidden;
        }

        .reg_container {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            height: calc(100vh - 60px);
            overflow: hidden;
            padding: 15px;
            display: flex;
            justify-content: center;
        }

        .registration-form {
            max-width: 800px;
            width: 100%;
            margin: 0;
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            height: calc(100vh - 120px);
            overflow: hidden;
        }

        .section {
            background: #fff;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            display: none;
            height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .section h3 {
            color: #2c3e50;
            margin: 0 0 20px 0;
            font-size: 18px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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

        .save {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .save:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .upload-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            margin-top: 12px;
        }

        .upload-section label {
            display: block;
            margin: 15px 0 5px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 14px;
        }

        .upload-section input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
            font-size: 14px;
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

        /* Keep only section scrollbar styling */
        .section::-webkit-scrollbar {
            width: 8px;
        }

        .section::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .section::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .section::-webkit-scrollbar-thumb:hover {
            background: #666;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .reg_container {
                padding: 10px;
                margin-top: 105px;
            }

            .registration-form {
                padding: 15px;
                height: calc(100vh - 100px);
            }

            .section {
                padding: 12px;
                height: calc(100vh - 180px);
            }
        }

        @media (max-height: 600px) {
            .section {
                height: calc(100vh - 160px);
            }
        }
    </style>
</head>
<body>
    <div class="reg_container">
    <form class="registration-form" id="registrationForm">
        <div class="section active" id="personalInfo">
            <h3>1. Personal Information</h3>
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
            <label for="middleName">Middle Name</label>
            <input type="text" id="middleName" name="middleName" placeholder="Middle Name">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
            <label for="aadharNumber">Aadhar Number</label>
            <input type="text" id="aadharNumber" name="aadharNumber" placeholder="Aadhar Number" required>
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= $_SESSION['Email'] ?>" readonly>
            <label for="contactNumber">Contact Number</label>
            <input type="tel" id="contactNumber" name="contactNumber" placeholder="Contact Number" inputmode="numeric" required>
            <a href="javascript:void(0);" class="save" onclick="validateAndSave(event, 'personalInfo', 'addressSection')">Save</a>
            <p id="formMessage"></p>
        </div>

        <div class="section" id="addressSection">
            <h3>2. Address Details</h3>
            <label>House Number/Name</label>
            <input type="text" id="houseNumber" placeholder="House Number/Name" required>
            <label>Area and Village</label>
            <input type="text" id="areaVillage" placeholder="Area and Village" required>
            <label>City Name</label>
            <input type="text" id="city" placeholder="City Name" required>
            <label>District</label>
            <input type="text" id="district" placeholder="District" required>
            <label>State/Province</label>
            <input type="text" id="state" placeholder="State/Province" required>
            <label>Pin Code</label>
            <input type="text" id="pinCode" placeholder="Pin Code" required>
            <label>Landmark/Nearest Location</label>
            <input type="text" id="landmark" placeholder="Landmark/Nearest Location">
            <a href="javascript:void(0);" class="save" onclick="validateAndSave(event, 'addressSection', 'academicDetails')">Save</a>
            <p id="formMessage"></p>
        </div>

        <div class="section" id="academicDetails">
            <h3>3. Academic Details</h3>
            <label>College Name</label>
            <input type="text" id="collegeName" placeholder="College Name" required>
            <label>Course Name</label>
            <input type="text" id="courseName" placeholder="Course Name" required>
            <label>Year of Passout</label>
            <input type="text" id="yearOfPassout" placeholder="Year of Passout" required>
            <label>CGPA/Percentage</label>
            <input type="text" id="cgpa" placeholder="CGPA/Percentage" required>
            <label>SSLC Marks</label>
            <input type="text" id="sslcMarks" placeholder="SSLC Marks" required>
            <label>PUC Marks</label>
            <input type="text" id="pucMarks" placeholder="PUC Marks" required>
            <a href="javascript:void(0);" class="save" onclick="validateAndSave(event, 'academicDetails', 'workExperience')">Save</a>
            <p id="formMessage"></p>
        </div>

        <div class="section" id="workExperience">
            <h3>4. Work Experience</h3>
            <label>Do you have any work experience?</label>
            <select id="experienceSelect" onchange="toggleExperience()">
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <div id="experienceDetails" class="hidden">
                <label>Internships/Part-time Jobs</label>
                <input type="text" id="internships" placeholder="Internships/Part-time Jobs">

                <label>Company Name & Duration</label>
                <input type="text" id="companyName" placeholder="Company Name & Duration">

                <label>Upload Experience Certificate (PDF)</label>
                <input type="file" id="certificate" accept="application/pdf">
            </div>

            <a href="javascript:void(0);" class="save" onclick="validateWorkExperience(event,'experienceDetails','documentUpload')">Save</a>
            <p id="workExperienceMessage"></p>
        </div>

        <div class="section" id="documentUpload">
            <h3>5. Document Upload</h3>
            <div class="upload-section">
                <label for="aadhar">Aadhar PDF</label>
                <input type="file" id="aadhar" accept="application/pdf" required>
                <label for="pan">PAN PDF</label>
                <input type="file" id="pan" accept="application/pdf" required>
                <label for="photo">Photo</label>
                <input type="file" id="photo" accept="image/*" required>
                <label for="signature">Signature</label>
                <input type="file" id="signature" accept="image/*" required>
                <label for="resume">Resume (PDF)</label>
                <input type="file" id="resume" accept="application/pdf" required>
                <a href="javascript:void(0);" class="save" onclick="saveData()">Save</a>
                <div id="notification" class="notification"></div>
            </div>
        </div>

    </form>
    </div>

    <script>
        function validateAndSave(event, currentSection, nextSection) {
            event.preventDefault();
            const section = document.getElementById(currentSection);
            const inputs = section.querySelectorAll("input, select");
            let isValid = true;
            let missingFields = [];
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    missingFields.push(input.placeholder || input.name);
                    isValid = false;
                }
            });
            
            const messageElement = section.querySelector("#formMessage");
            
            if (!isValid) {
                messageElement.textContent = "Please fill out the following fields: " + missingFields.join(", ");
                messageElement.className = "error-message";
            } else {
                // Additional validation for email and contact number
                const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
                if (!emailPattern.test(document.getElementById('email').value)) {
                    messageElement.textContent = "Please enter a valid Gmail address.";
                    messageElement.className = "error-message";
                    isValid = false;
                }
                
                const contactPattern = /^[0-9]{10}$/;
                if (!contactPattern.test(document.getElementById('contactNumber').value)) {
                    messageElement.textContent = "Contact number should be exactly 10 digits.";
                    messageElement.className = "error-message";
                    isValid = false;
                }
                
                if (isValid) {
                    messageElement.textContent = "Section saved successfully!";
                    messageElement.className = "success-message";
                    setTimeout(() => {
                        section.classList.remove("active");
                        if (nextSection) {
                            document.getElementById(nextSection).classList.add("active");
                        }
                    }, 1000);
                }
            }
        }

        function saveData() {
            let missingFields = [];

            const aadhar = document.getElementById("aadhar").files[0];
            const pan = document.getElementById("pan").files[0];
            const photo = document.getElementById("photo").files[0];
            const signature = document.getElementById("signature").files[0];
            const resume = document.getElementById("resume").files[0];
            const certificate = document.getElementById("certificate").files[0];

            if (!aadhar) missingFields.push("Aadhar PDF");
            if (!pan) missingFields.push("PAN PDF");
            if (!photo) missingFields.push("Photo");
            if (!signature) missingFields.push("Signature");
            if (!resume) missingFields.push("Resume PDF");

            const notification = document.getElementById("notification");
            if (missingFields.length > 0) {
                notification.innerHTML = "Please upload the following files: <br> " + missingFields.join(", ");
                notification.className = "notification error-msg";
                notification.style.display = "block";
                return;
            }

            // Create FormData object
            const formData = new FormData();
            
            // Add personal information
            formData.append('first_name', document.getElementById('firstName').value);
            formData.append('middle_name', document.getElementById('middleName').value);
            formData.append('last_name', document.getElementById('lastName').value);
            formData.append('aadhar_number', document.getElementById('aadharNumber').value);
            formData.append('dob', document.getElementById('dob').value);
            formData.append('gender', document.getElementById('gender').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('contact_number', document.getElementById('contactNumber').value);

            // Add address details
            formData.append('house_number', document.getElementById('houseNumber').value);
            formData.append('area_village', document.getElementById('areaVillage').value);
            formData.append('city', document.getElementById('city').value);
            formData.append('district', document.getElementById('district').value);
            formData.append('state', document.getElementById('state').value);
            formData.append('pin_code', document.getElementById('pinCode').value);
            formData.append('landmark', document.getElementById('landmark').value);

            // Add academic details
            formData.append('college_name', document.getElementById('collegeName').value);
            formData.append('course_name', document.getElementById('courseName').value);
            formData.append('year_of_passout', document.getElementById('yearOfPassout').value);
            formData.append('cgpa', document.getElementById('cgpa').value);
            formData.append('sslc_marks', document.getElementById('sslcMarks').value);
            formData.append('puc_marks', document.getElementById('pucMarks').value);

            // Add work experience
            formData.append('has_experience', document.getElementById('experienceSelect').value);
            formData.append('internships', document.getElementById('internships').value);
            formData.append('company_name', document.getElementById('companyName').value);

            // Add files
            formData.append('aadhar', aadhar);
            formData.append('pan', pan);
            formData.append('photo', photo);
            formData.append('signature', signature);
            formData.append('resume', resume);
            formData.append('certificate', certificate);

            // Send data to PHP script
            fetch('pages/save_registration.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notification.innerHTML = "Registration completed successfully!";
                    notification.className = "notification success-msg";
                    notification.style.display = "block";
                    
                    setTimeout(() => {
                        window.location.href = 'pages/summary.php?id=' + data.registration_id;
                    }, 1500);
                } else {
                    notification.innerHTML = "Error: " + data.message;
                    notification.className = "notification error-msg";
                    notification.style.display = "block";
                }
            })
            .catch(error => {
                notification.innerHTML = "Error occurred while saving data.";
                notification.className = "notification error-msg";
                notification.style.display = "block";
            });
        }

        function toggleExperience() {
            document.getElementById('experienceDetails').classList.toggle('hidden', document.getElementById('experienceSelect').value !== 'Yes');
        }

        function validateWorkExperience(event) {
            event.preventDefault();

            const experienceSelect = document.getElementById("experienceSelect");
            const messageElement = document.getElementById("workExperienceMessage");

            let missingFields = [];

            if (!experienceSelect.value) {
                missingFields.push("Work Experience Selection");
            }

            if (experienceSelect.value === "Yes") {
                const fields = [
                    { id: "internships", name: "Internships/Part-time Jobs" },
                    { id: "companyName", name: "Company Name & Duration" },
                    { id: "certificate", name: "Experience Certificate" }
                ];

                fields.forEach(field => {
                    const input = document.getElementById(field.id);
                    if (!input.value.trim()) {
                        missingFields.push(field.name);
                    }
                });
            }

            if (missingFields.length > 0) {
                messageElement.textContent = "Please fill out the following fields: " + missingFields.join(", ");
                messageElement.className = "error-message";
            } else {
                messageElement.textContent = "Work Experience details saved successfully!";
                messageElement.className = "success-message";

                setTimeout(() => {
                    document.getElementById('workExperience').classList.remove("active");
                    document.getElementById('documentUpload').classList.add("active");
                }, 1000);
            }
        }
    </script>
</body>
</html>