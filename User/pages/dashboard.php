<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            margin: 0;
            min-height: 100vh;
        }

        .dashboard-container {
            padding: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .dashboard-box {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 8px;
            box-shadow: -2px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .dashboard-box:hover {
            transform: translate(2px, -5px);
            box-shadow: -2px 5px 10px rgba(0, 0, 0, 0.15);
        }

        .box-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(240, 242, 245, 0.8);
        }

        .box-header i {
            font-size: 20px;
            padding: 10px;
            border-radius: 8px;
            color: #2c3e50;
            background: transparent;
        }

        .box-header h2 {
            font-size: 18px;
            color: #2c3e50;
            margin: 0;
        }

        .companies-icon {
            background: transparent !important;
            color: #2c3e50 !important;
        }

        .jobs-icon {
            background: transparent !important;
            color: #2c3e50 !important;
        }

        .reports-icon {
            background: transparent !important;
            color: #2c3e50 !important;
        }

        .box-content {
            color: #666;
        }

        .company-item,
        .job-item,
        .report-item {
            padding: 10px 0;
            border-bottom: 1px solid rgba(240, 242, 245, 0.8);
            transition: background-color 0.2s;
        }

        .company-item:hover,
        .job-item:hover,
        .report-item:hover {
            background-color: rgba(248, 249, 250, 0.5);
        }

        .company-item:last-child,
        .job-item:last-child,
        .report-item:last-child {
            border-bottom: none;
        }

        .company-name,
        .job-title,
        .report-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .company-status,
        .job-company,
        .report-date {
            font-size: 13px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-applied {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: #2e7d32;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            color: #f57c00;
        }

        .job-company i,
        .report-date i {
            color: #2c3e50;
        }

        .view-all {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .view-all:hover {
            background-color: rgba(44, 62, 80, 0.1);
            color: #000;
            text-decoration: none;
        }

        .report-score {
            font-size: 14px;
            color: #2c3e50;
            margin-top: 5px;
        }

        .score-high {
            color: #2e7d32;
        }

        .score-medium {
            color: #f57c00;
        }

        .score-low {
            color: #c62828;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="dashboard-grid">
            <!-- Companies Applied Box -->
            <div class="dashboard-box">
                <div class="box-header">
                    <i class="fas fa-building companies-icon"></i>
                    <h2>Companies Applied</h2>
                </div>
                <div class="box-content">
                    <?php
                    include './db.php';
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $email = $_SESSION['Email'];
                    $sql = "
                            SELECT companies.name AS company_name, applications.created_at
                            FROM applications
                            JOIN companies ON applications.company_id = companies.id
                            WHERE applications.student_email = ? LIMIT 3
                        ";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="company-item">';
                            echo '<div class="company-name">' . htmlspecialchars($row['company_name']) . '</div>';
                            echo '<span>• ' . date('M d, Y', strtotime($row['created_at'])) . '</span>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="company-item">';
                        echo '<div class="company-name">No applications yet</div>';
                        echo '</div>';
                    }
                    ?>
                    <a href="home.php?page=status" class="view-all">View All Applications</a>
                </div>
            </div>
            <div class="dashboard-box">
                <div class="box-header">
                    <i class="fas fa-briefcase jobs-icon"></i>
                    <h2>Latest Jobs</h2>
                </div>
                <div class="box-content">
                    <?php

                    $email = $_SESSION['Email'] ?? '';

                    if ($email) {
                        if ($stmt = $conn->prepare("SELECT stream FROM reg_hist WHERE email = ?")) {
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $stmt->bind_result($stream);
                            $stmt->fetch();
                            $stmt->close();

                            // Decide query based on stream
                            if ($stream == "BCA" || $stream == "BSC") {
                                $query = "SELECT * FROM companies WHERE compnayType = 'IT' OR compnayType = 'All' LIMIT 3";
                            } else {
                                $query = "SELECT * FROM companies WHERE compnayType = 'Non_IT' OR compnayType = 'All' LIMIT 3";
                            }

                            $result = mysqli_query($conn, $query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<div class="job-item">
                                        <div class="job-title">' . htmlspecialchars($row['role']) . '</div>
                                        <div class="job-company">
                                            <i class="fas fa-building"></i> ' . htmlspecialchars($row['name']) . '
                                            <span>• Deadline ' . htmlspecialchars($row['deadline']) . '</span>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo '<div class="job-item">';
                                echo '<div class="job-title">No Jobs available</div>';
                                echo '</div>';
                            }
                            echo '<a href="home.php?page=companyList" class="view-all">View All Jobs</a>
                                </div>
                                </div>';
                        }
                    }

                    $conn->close();
                    ?>


                    <!-- Reports & Feedback Box -->
                    <div class="dashboard-box">
                        <div class="box-header">
                            <i class="fas fa-chart-line reports-icon"></i>
                            <h2>Quiz Results</h2>
                        </div>
                        <div class="box-content">
                            <?php
                            include './db.php';
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $email = $_SESSION['Email'];
                            $sql = "SELECT * FROM quiz_results WHERE email = '$email' ORDER BY submission_date DESC LIMIT 3";
                            $result = mysqli_query($conn, $sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $score_percentage = ($row['score'] / $row['total_questions']) * 100;
                                    $score_class = $score_percentage >= 70 ? 'score-high' : ($score_percentage >= 40 ? 'score-medium' : 'score-low');

                                    echo '<div class="report-item">';
                                    echo '<div class="report-title">' . htmlspecialchars($row['book_title']) . '</div>';
                                    echo '<div class="report-score ' . $score_class . '">';
                                    echo '<i class="fas fa-star"></i> Score: ' . $row['score'] . '/' . $row['total_questions'];
                                    echo '</div>';
                                    echo '<div class="report-date">';
                                    echo '<i class="fas fa-calendar-alt"></i> ' . date('F j, Y', strtotime($row['submission_date']));
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="report-item">';
                                echo '<div class="report-title">No quiz results available</div>';
                                echo '</div>';
                            }
                            $conn->close();
                            ?>
                            <a href="pages/view_results.php" class="view-all">View All Results</a>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>