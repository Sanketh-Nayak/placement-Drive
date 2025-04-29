<?php
$conn = mysqli_connect('localhost', 'root', '', 'admin_db');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$email = $_SESSION['Email'];

$sql = "SELECT a.*, c.name AS company_name, c.role AS company_role
        FROM applications a
        JOIN companies c ON a.company_id = c.id
        WHERE a.student_email = '$email'
        ORDER BY a.created_at DESC";

$result = mysqli_query($conn, $sql);

$status_map = ['pending' => 0, 'approved' => 1, 'rejected' => 0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Application Status Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    .status-container {
      width: 85%;
      max-width: 900px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 16px;
      background: #ffffff;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
      transition: box-shadow 0.3s ease;
    }

    .status-container:hover {
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
    }

    .Application-heading {
      text-align: center;
      font-size: 28px;
      color: #2c3e50;
      margin-bottom: 30px;
      padding-bottom: 10px;
      border-bottom: 2px solid #eee;
    }

    .status-track {
      display: flex;
      justify-content: space-between;
      position: relative;
      margin-bottom: 40px;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      flex: 1;
      z-index: 1;
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .step:hover {
      transform: scale(1.05);
    }

    .step::after {
      content: "";
      position: absolute;
      top: 22px;
      right: -50%;
      width: 100%;
      height: 4px;
      background: #e0e0e0;
      z-index: -1;
      transition: background 0.4s ease;
    }

    .step:last-child::after {
      content: none;
    }

    .step.filled::after {
      background: linear-gradient(to right, #007bff, #00c6ff);
    }

    .circle {
      width: 45px;
      height: 45px;
      background: white;
      border: 3px solid #dcdcdc;
      border-radius: 50%;
      color: #999;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 16px;
      transition: all 0.4s ease;
      position: relative;
    }

    /* Tooltip on step hover */
    .step:hover::before {
      content: attr(data-tooltip);
      position: absolute;
      top: -40px;
      background-color: rgba(0, 0, 0, 0.75);
      color: #fff;
      padding: 6px 10px;
      font-size: 12px;
      border-radius: 4px;
      white-space: nowrap;
      pointer-events: none;
      opacity: 1;
      transition: opacity 0.3s ease;
    }

    .step.active .circle {
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: white;
      border: none;
      box-shadow: 0 0 12px rgba(0, 191, 255, 0.6);
    }

    .step.filled .circle {
      background: #007bff;
      color: #fff;
      border: none;
    }

    .label {
      margin-top: 12px;
      font-size: 14px;
      color: #34495e;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-align: center;
    }

    .status-details {
      background: #f9f9f9;
      padding: 20px 25px;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.03);
      transition: background 0.3s ease;
    }

    .status-details:hover {
      background: #fdfdfd;
    }

    .status-details p {
      margin: 12px 0;
      font-size: 15px;
      color: #333;
      line-height: 1.6;
    }

    @media (max-width: 768px) {
      .status-track {
        flex-direction: column;
        align-items: flex-start;
      }

      .step {
        flex-direction: row;
        align-items: center;
        margin-bottom: 30px;
      }

      .step::after {
        top: 50%;
        left: 45px;
        width: 3px;
        height: 100%;
        transform: translateY(-50%);
      }

      .step:last-child::after {
        height: 0;
      }

      .circle {
        margin-right: 15px;
      }

      .label {
        margin: 0;
      }

      .step:hover::before {
        left: 60px;
        top: -20px;
      }
    }

    .step:hover::before {
      content: attr(data-tooltip);
      position: absolute;
      bottom: 60px;
      background: #333;
      color: #fff;
      padding: 6px 10px;
      border-radius: 6px;
      white-space: nowrap;
      font-size: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
      transform: translateX(-50%);
      left: 50%;
      opacity: 0;
      animation: fadeIn 0.3s forwards;
      z-index: 10;
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <div class="body">
   
  <?php 
if ($result && mysqli_num_rows($result) > 0):
  while ($row = mysqli_fetch_assoc($result)):
    $application_status = $row['status'];
    $company_name = $row['company_name'];
    $company_role = $row['company_role'];
    $current_step = $status_map[$application_status];
    $container_id = 'status_' . $row['id'];
?>
  <div class="status-container" id="<?php echo $container_id; ?>" data-step="<?php echo $current_step; ?>">
    <h2 class="Application-heading">Application to <?php echo $company_name; ?> (<?php echo $company_role; ?>)</h2>

    <div class="status-track">
      <div class="step" data-tooltip="Your application to <?php echo $company_name; ?> for the role of <?php echo $company_role; ?> has been submitted.">
        <div class="circle">1</div>
        <div class="label">Application Submitted</div>
      </div>
      <div class="step" data-tooltip="Aptitude test for <?php echo $company_name; ?> is in progress.">
        <div class="circle">2</div>
        <div class="label">Aptitude</div>
      </div>
      <div class="step" data-tooltip="Interview for the <?php echo $company_role; ?> role at <?php echo $company_name; ?> has been scheduled.">
        <div class="circle">3</div>
        <div class="label">Interview</div>
      </div>
      <div class="step" data-tooltip="Final decision for your application at <?php echo $company_name; ?>.">
        <div class="circle">4</div>
        <div class="label">Offer/Rejection</div>
      </div>
    </div>

    <div class="status-details">
      <p class="current-status"></p>
      <p class="status-message"></p>
    </div>
  </div>

  <script>
    (function () {
      const container = document.getElementById('<?php echo $container_id; ?>');
      const steps = container.querySelectorAll('.step');
      const statusLabel = container.querySelector('.current-status');
      const statusMessage = container.querySelector('.status-message');
      const currentStep = parseInt(container.getAttribute('data-step'));

      const messages = [
        {
          label: "Application Submitted",
          message: "Your application to <?php echo $company_name; ?> for the <?php echo $company_role; ?> role has been received."
        },
        {
          label: "Aptitude",
          message: "You're progressing through the Aptitude Test for <?php echo $company_name; ?>."
        },
        {
          label: "Interview",
          message: "An interview for the <?php echo $company_role; ?> role at <?php echo $company_name; ?> has been scheduled."
        },
        {
          label: "<?php echo ucfirst($application_status); ?>",
          message: "<?php
            if ($application_status === 'approved') {
              echo ' Congratulations! You have been selected by ' . $company_name . ' for the ' . $company_role . ' role.';
            } elseif ($application_status === 'rejected') {
              echo ' Unfortunately, your application for ' . $company_role . ' at ' . $company_name . ' was not successful.';
            } else {
              echo ' Your application is still in progress.';
            }
          ?>"
        }
      ];

      steps.forEach((step, index) => {
        step.classList.remove('active', 'filled');
        if (index < currentStep) step.classList.add('filled');
        if (index === currentStep) step.classList.add('active');
      });

      statusLabel.textContent = messages[currentStep].label;
      statusMessage.textContent = messages[currentStep].message;
    })();
  </script>

<?php endwhile; else: ?>
  <center><h3 style="margin-top: 25px ;">You have not applied to any company yet.</h3></center>
<?php endif; ?>

  </div>
</body>

</html>