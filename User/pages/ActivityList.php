<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Books</title>
    <style>
        .book-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .book-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            padding: 20px;
            transition: transform 0.2s ease-in-out;
        }

        .book-card h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .book-card p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .book-card button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 16px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .book-card button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <div class="book-container">
        <?php
        include './db.php';

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $_SESSION['Email']; 

        $sql = "SELECT * FROM books";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookId = $row['book_id'];
                $check_sql = "SELECT * FROM quiz_results WHERE email = '$email' AND book_title = '" . mysqli_real_escape_string($conn, $row['title']) . "'";
                $check_result = $conn->query($check_sql);
                $has_applied = $check_result->num_rows > 0;

                echo '<div class="book-card">';
                echo '<h2>Book Title: <span>' . htmlspecialchars($row['title']) . '</span></h2>';
                echo '<p>Number of Questions: <span>' . $row['number_of_questions'] . '</span></p>';

                if ($has_applied) {
                    echo '<div class="applied-badge" style="color: green; font-weight: bold; margin-top: 10px;">
                    <i class="fas fa-check-circle"></i> Completed
                  </div>';
                } else {
                    echo '<button onclick="startQuiz(' . $bookId . ')">Start</button>';
                }

                echo '</div>'; 
            }
        } else {
            echo '<p>No Aptitude found.</p>';
        }

        $conn->close();
        ?>
    </div>


    <script>
        function startQuiz(bookId) {
            window.location.href = 'pages/start_quiz.php?book_id=' + bookId;
        }
    </script>

</body>

</html>