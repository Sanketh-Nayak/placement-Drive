<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "admin_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Get the stream of the current student
$query = "SELECT * from feedbacks";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        .notification-container {
            width: 90%;
            max-width: 1000px;
            margin: 20px auto;
            background: #f9f9f0;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            text-align: center;
            margin: -25px -25px 25px -25px;
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 3px solid #eaeaea;
            border-radius: 10px 10px 0 0;
        }

        .page-header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            display: inline-block;
        }

        .page-header h2:after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: #007bff;
            margin: 8px auto 0;
            border-radius: 2px;
        }

        .notification {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            border: 1px solid #eaeaea;
            transition: all 0.3s ease;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.08);

        }

        .notification:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .notification.unread {
            border-left: 6px solid #007bff;
            background: white;
        }

        .notification-content {
            width: 100%;
            font-family: 'Calibri';
            display: flex;
            background-color: #fff;
        }

        .left-block {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .right-block {
            background-color: #E6E6FA;
            padding: 8px 10px;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .notification p {
            margin: 0;
            color: #2c3e50;
            font-size: 16px;
            line-height: 1.5;
        }

        .time {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }

        .mark-read {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .mark-read:hover {
            background: rgba(0, 123, 255, 0.1);
            text-decoration: none;
        }

        /* Empty state */
        .no-notifications {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .notification-container {
                width: 95%;
                padding: 15px;
                margin: 10px auto;
            }

            .notification {
                padding: 12px 15px;
            }

            h2 {
                font-size: 1.2rem;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .notification {
                flex-direction: column;
                align-items: flex-start;
            }

            .notification-content {
                padding-right: 0;
                margin-bottom: 10px;
            }

            .mark-read {
                align-self: flex-end;
            }
        }

        /* Add these new styles */
        .score {
            font-size: 16px;
            margin: 8px 0;
            font-weight: bold;
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
    <div class="notification-container">
        <div class="page-header">
            <h2>Student Feedbacks</h2>
        </div>
        
        <?php
            while ($row = $result->fetch_assoc()) {
            $createdAt = new DateTime($row['created_at']);
            $createdAtFormatted = $createdAt->format('F j, Y, g:i a'); // Example format: "March 15, 2023, 4:30 pm"
        ?>
            <div class="notification unread">
                <div class="notification-content">
                    <div class="left-block">
                        <p><?= htmlspecialchars($row['message'])?></p>
                        <div class="time"><?= htmlspecialchars($createdAtFormatted) ?></div>
                    </div>
                    <div class="right-block">
                        <p><b><?= $row['name']?>&nbsp;(<?= $row['stream']?>)</b></p>
                        <p><?= htmlspecialchars($row['email']) ?></p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <!-- <button class="mark-read">Mark as read</button> -->
    </div>

    <!-- Student Scores Section -->
    <div class="notification-container">
        <div class="page-header">
            <h2>Student Quiz Scores</h2>
        </div>
        
        <?php
        // Query to get quiz results
        $quiz_query = "SELECT qr.*, rs.fullname, rs.stream 
        FROM quiz_results qr 
        JOIN regd_studs rs ON qr.email = rs.email 
        ORDER BY qr.submission_date DESC";

        $quiz_result = mysqli_query($conn, $quiz_query);

        if ($quiz_result && $quiz_result->num_rows > 0) {
            while ($quiz_row = $quiz_result->fetch_assoc()) {
                $score_percentage = ($quiz_row['score'] / $quiz_row['total_questions']) * 100;
                $score_class = $score_percentage >= 70 ? 'score-high' : 
                             ($score_percentage >= 40 ? 'score-medium' : 'score-low');
        ?>
            <div class="notification">
                <div class="notification-content">
                    <div class="left-block">
                        <p><strong><?= htmlspecialchars($quiz_row['book_title']) ?></strong></p>
                        <div class="score <?= $score_class ?>">
                            Score: <?= $quiz_row['score'] ?>/<?= $quiz_row['total_questions'] ?> 
                            (<?= number_format($score_percentage, 1) ?>%)
                        </div>
                        <div class="time">
                            <?= date('F j, Y, g:i a', strtotime($quiz_row['submission_date'])) ?>
                        </div>
                    </div>
                    <div class="right-block">
                        <p><b><?= htmlspecialchars($quiz_row['fullname']) ?>&nbsp;(<?= htmlspecialchars($quiz_row['stream']) ?>)</b></p>
                        <p><?= htmlspecialchars($quiz_row['email']) ?></p>
                    </div>
                </div>
            </div>
        <?php
            }
        } else {
            echo '<div class="notification">
                    <div class="notification-content">
                        <div class="left-block">
                            <p>No quiz results available.</p>
                        </div>
                    </div>
                  </div>';
        }
        ?>
    </div>
</body>
</html>
