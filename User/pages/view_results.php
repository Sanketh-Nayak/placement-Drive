<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Quiz Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-button {
            text-decoration: none;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-button:hover {
            color: #1a252f;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .result-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .result-card:hover {
            transform: translateY(-5px);
        }

        .book-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .score {
            font-size: 16px;
            margin: 10px 0;
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

        .date {
            color: #666;
            font-size: 14px;
        }

        .no-results {
            text-align: center;
            color: #666;
            font-size: 18px;
            grid-column: 1 / -1;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>All Quiz Results</h1>
            <a href="../home.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="results-grid">
            <?php
            session_start();
            include './db.php';

            $email = $_SESSION['Email'];
            $sql = "SELECT * FROM quiz_results WHERE email = '$email' ORDER BY submission_date DESC";
            $result = mysqli_query($conn, $sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $score_percentage = ($row['score'] / $row['total_questions']) * 100;
                    $score_class = $score_percentage >= 70 ? 'score-high' : 
                                 ($score_percentage >= 40 ? 'score-medium' : 'score-low');
                    
                    echo '<div class="result-card">';
                    echo '<div class="book-title">' . htmlspecialchars($row['book_title']) . '</div>';
                    echo '<div class="score ' . $score_class . '">';
                    echo '<i class="fas fa-star"></i> Score: ' . $row['score'] . '/' . $row['total_questions'];
                    echo ' (' . number_format($score_percentage, 1) . '%)';
                    echo '</div>';
                    echo '<div class="date">';
                    echo '<i class="fas fa-calendar-alt"></i> ' . date('F j, Y', strtotime($row['submission_date']));
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="no-results">No quiz results available</div>';
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html> 