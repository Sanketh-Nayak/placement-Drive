<?php
include './db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $application_id = $conn->real_escape_string($_POST["application_id"]);
    $company_id = $conn->real_escape_string($_POST["company_id"]);
    $student_email = $_SESSION['Email'];
    
    $sql = "INSERT INTO applications (student_email, company_id, application_id, status) 
            VALUES ('$student_email', '$company_id', '$application_id', 'pending')";
    
    if ($conn->query($sql) === TRUE) {
        $success_message = "Application submitted successfully!";
        $message = "Your application (ID: ".$application_id.")  has been submitted successfully.";

        $stmt = $conn->prepare("INSERT INTO notification (email, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $student_email, $message);
        $stmt->execute();
    } else {
        $error_message = "Error submitting application: " . $conn->error;
    }
}

// Fetch Companies from Database
$email = $_SESSION['Email'];

if ($stmt = $conn->prepare("SELECT stream FROM reg_hist WHERE email = ?")) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stream);
    $stmt->fetch();
    $stmt->close();

    if ($stream == "BCA" || $stream == "BSC") {
        // Display IT companies
        $query = "SELECT * FROM companies WHERE compnayType='IT' or compnayType='All'";
    } else{
        $query = "SELECT * FROM companies WHERE compnayType='All' or compnayType='Non_IT'";
    }

    $result = $conn->query($query);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Recruitment</title>
    <style>
        .bx {
            height: calc(95vh - 90px);
            width: 85%;
            color: black;
            background-color: #f4f4f4;
            margin-left: 50px;
            padding: 20px;
            text-align: center;
            border-radius: 35px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .container {
            max-height: 80vh;
            overflow-y: auto;
            padding-right: 10px;
        }
        .company {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            margin: 15px 0;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
            position: relative;
        }

        .company:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .company h3 {
            margin-bottom: 12px;
            color: #222;
            font-size: 22px;
            font-weight: bold;
            border-left: 5px solid #007BFF;
            padding-left: 10px;
        }

        .company-details {
            margin-top: 15px;
            padding: 15px;
            background: rgba(245, 245, 245, 0.9);
            border-radius: 12px;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.05);
            font-size: 15px;
            color: #333;
            line-height: 1.8;
        }

        .company-details p {
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .company-details p strong {
            min-width: 100px;
            color: #555;
            font-weight: 600;
        }

        .company-details p::before {
            content: "✔️";
            font-size: 14px;
            color: #007BFF;
        }

        .info-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #007BFF, #0056b3);
            color: white;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-button:hover {
            background: linear-gradient(135deg, #0056b3, #003f7f);
        }

        .application-form {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .application-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .submit-btn {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: #218838;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .container::-webkit-scrollbar {
            width: 8px;
        }
        .container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }
        .container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .applied-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
        }

        .button-disabled {
            background: #cccccc !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .application-status {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="bx">
        <div class="container">
            <h2>Companies for Recruitment</h2>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?= $success_message ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?= $error_message ?></div>
            <?php endif; ?>

            <?php while ($row = $result->fetch_assoc()) : 
                // Check if user has already applied
                $check_sql = "SELECT status FROM applications WHERE student_email = '$email' AND company_id = " . $row['id'];
                $check_result = $conn->query($check_sql);
                $has_applied = $check_result->num_rows > 0;
                $application_status = $has_applied ? $check_result->fetch_assoc()['status'] : '';
            ?>
                <div class="company">
                    <h3><?= htmlspecialchars($row["name"]) ?></h3>
                    <div class="company-details">
                        <p><strong>Location:</strong> <?= htmlspecialchars($row["location"]) ?></p>
                        <p><strong>Role:</strong> <?= htmlspecialchars($row["role"]) ?></p>
                        <p><strong>Deadline:</strong> <?= htmlspecialchars($row["deadline"]) ?></p>
                        
                        <?php if ($has_applied): ?>
                            <div class="applied-badge">
                                <i class="fas fa-check-circle"></i> Applied
                            </div>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($row["company_url"]) ?>" 
                               class="info-button" 
                               target="_blank" 
                               onclick="showApplicationForm(<?= $row['id'] ?>)"
                               id="apply-btn-<?= $row['id'] ?>">
                                Apply Now
                            </a>
                        <?php endif; ?>
                        
                        <div class="application-form" id="form-<?= $row['id'] ?>">
                            <form method="POST" action="" onsubmit="handleSubmit(event, <?= $row['id'] ?>)">
                                <input type="hidden" name="company_id" value="<?= $row['id'] ?>">
                                <div class="form-group">
                                    <label for="application_id">Application ID/Reference Number:</label>
                                    <input type="text" id="application_id" name="application_id" required placeholder="Enter your application ID">
                                </div>
                                <button type="submit" class="submit-btn" id="submit-btn-<?= $row['id'] ?>">Submit Application</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <?php $conn->close(); ?>
        </div>
    </div>

    <script>
        function showApplicationForm(companyId) {
            document.querySelectorAll('.application-form').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById('form-' + companyId).classList.add('active');
        }

        function handleSubmit(event, companyId) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('submit-btn-' + companyId);
            const applyBtn = document.getElementById('apply-btn-' + companyId);
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            
            // Submit the form
            form.submit();
            
            // Disable apply button and show applied status
            if (applyBtn) {
                applyBtn.classList.add('button-disabled');
                applyBtn.innerHTML = '<i class="fas fa-check-circle"></i> Applied';
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.textContent.trim() !== '') {
                    alert.style.display = 'block';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 5000);
                }
            });
        });
    </script>
</body>
</html>
