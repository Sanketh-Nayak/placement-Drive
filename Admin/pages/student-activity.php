<?php

$conn = @mysqli_connect('localhost', 'root', '', 'admin_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['sbtn'])) {
    $title = $_POST['bank_name'];
    $no_of_Question = $_POST['total_questions'];

    $qry = "INSERT INTO books (title, number_of_questions) VALUES ('$title', '$no_of_Question')";
    $res = mysqli_query($conn, $qry);

    if ($res) {
        $book_id = mysqli_insert_id($conn);
        $success = true;

        for ($i = 1; $i <= $no_of_Question; $i++) {
            $question_text = mysqli_real_escape_string($conn, $_POST["question_$i"]);
            $option_a = mysqli_real_escape_string($conn, $_POST["option_a_$i"]);
            $option_b = mysqli_real_escape_string($conn, $_POST["option_b_$i"]);
            $option_c = mysqli_real_escape_string($conn, $_POST["option_c_$i"]);
            $option_d = mysqli_real_escape_string($conn, $_POST["option_d_$i"]);
            $correct = mysqli_real_escape_string($conn, $_POST["correct_$i"]);

            $sqry = "INSERT INTO questions (book_id, question_text, option_a, option_b, option_c, option_d, correct_answer) 
                     VALUES ('$book_id', '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct')";
            $res = mysqli_query($conn, $sqry);
            if (!$res) {
                $success = false;
            }
        }

        if ($success) {
            echo "<script>alert('Question bank and questions added successfully!');</script>";
        }
    } else {
        echo "<script>alert('Failed to create question bank');</script>";
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Activity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <main class="main-content1">
        <div class="container1">
            <!-- First div for the button -->
            <div class="action-section">
                <button class="btn btn-primary" id="addAptitudeBtn" onclick="showAddForm()">
                    <i class="fas fa-plus"></i> Add New Aptitude Question
                </button>
            </div>

            <!-- Add Aptitude Form (hidden by default) -->
            <div id="addAptitudeForm" style="display: none;">
                <h2>Create Question Bank</h2>

                <form id="questionBankForm" method="POST">
                    <!-- Bank Name and Question Count -->
                    <div class="form-group">
                        <label for="bank_name">Question Bank Name:</label>
                        <input type="text" id="bank_name" name="bank_name" required>
                    </div>

                    <div class="form-group">
                        <label for="total_questions">Number of Questions:</label>
                        <input type="number" id="total_questions" name="total_questions" min="1" max="50" required onchange="validateNumber(this)">
                    </div>

                    <button type="button" class="btn" onclick="createQuestionForm()">Generate Questions</button>

                    <!-- Questions will go here -->
                    <div id="questions-container" class="question-form-container" style="display:none;"></div>

                    <button type="submit" class="btn" name="sbtn" style="display:none;" id="submitBtn">Save All Questions</button>
                </form>

            </div>

            <!-- Second div for displaying aptitude questions list -->
            <div class="aptitude-list-section">
                <h2>Aptitude Questions List</h2>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th>Question Bank Name</th>
                            <th>Total Questions</th>
                            <th>Actions</th>
                        </tr>
                    <?php
                    $conn = mysqli_connect('localhost', 'root', '', 'admin_db');
                    if (!$conn) {
                        echo "<tr><td colspan='4'>Database connection failed</td></tr>";
                    } else {
                        $qry = "SELECT * FROM books ";
                        $res = mysqli_query($conn, $qry);
                        $rc = mysqli_num_rows($res);
                        if ($rc != 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['number_of_questions']) . "</td>";
                                echo "<td><button class='view-btn' onclick='viewQuestions(" . $row['book_id'] . ")'>View</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No question banks found.</td></tr>";
                        }

                        mysqli_close($conn);
                    }
                    ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Add this before closing body tag -->
    <div id="questionPopup" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <span class="close-popup" onclick="closePopup()">&times;</span>
            <h2>Questions</h2>
            <div id="questionsList"></div>
        </div>
    </div>

    <style>
        .main-content1 {
            padding: 20px;
        }

        .container1 {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .action-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .btn {
            background-color: #4CAF50;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            color: black;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .aptitude-list-section {
            margin-top: 20px;
        }

        .aptitude-list-section h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            color: black;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f9f9f9;
        }

        .view-btn {
            background-color: #2196F3;
            color: black;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .view-btn:hover {
            background-color: #1976D2;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .text-center {
            text-align: center;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 80px;
        }

        .question-container {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .question-header {
            font-weight: bold;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        .options-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .option-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .option-group input[type="radio"] {
            margin-right: 10px;
        }

        .question-form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Add these styles to your existing CSS */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .close-popup {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            transition: color 0.3s;
        }

        .close-popup:hover {
            color: #333;
        }

        .question-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .question-text {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .option {
            margin: 5px 0;
            padding: 5px;
        }

        .correct-answer {
            color: green;
            font-weight: bold;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .question-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .question-text {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .option {
            margin: 5px 0;
            padding: 5px 10px;
            background-color: #fff;
            border: 1px solid #e9ecef;
            border-radius: 3px;
        }

        .correct-answer {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 3px;
            font-weight: bold;
        }

        .send-button-container {
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            display: flex;
            justify-content: center;
        }

        .send-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .send-btn:hover {
            background-color: #218838;
        }

        .send-btn i {
            font-size: 14px;
        }
    </style>

    <script>
        function validateNumber(input) {
            if (input.value < 1) {
                input.value = 1;
            } else if (input.value > 50) {
                input.value = 50;
            }
        }

        function showAddForm() {
            document.getElementById('addAptitudeForm').style.display = 'block';
            document.getElementById('addAptitudeBtn').style.display = 'none';
        }

        function hideAddForm() {
            document.getElementById('addAptitudeForm').style.display = 'none';
            document.getElementById('addAptitudeBtn').style.display = 'inline-block';
        }

        function createQuestionForm() {
            const bankName = document.getElementById('bank_name').value.trim();
            const totalQuestions = parseInt(document.getElementById('total_questions').value);

            if (!bankName || !totalQuestions) {
                alert('Please fill in all fields');
                return;
            }

            const questionsContainer = document.getElementById('questions-container');
            questionsContainer.innerHTML = '';
            questionsContainer.style.display = 'block';

            for (let i = 1; i <= totalQuestions; i++) {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'question-container';
                questionDiv.innerHTML = `
            <div class="question-header">Question ${i}</div>
            <div class="form-group">
                <label for="question_${i}">Question Text:</label>
                <textarea id="question_${i}" name="question_${i}" required></textarea>
            </div>
            <div class="options-container">
                ${['A', 'B', 'C', 'D'].map(letter => `
                    <div class="form-group">
                        <label for="option_${letter.toLowerCase()}_${i}">Option ${letter}:</label>
                        <input type="text" id="option_${letter.toLowerCase()}_${i}" name="option_${letter.toLowerCase()}_${i}" required>
                        <div class="option-group">
                            <input type="radio" id="correct_${letter.toLowerCase()}_${i}" name="correct_${i}" value="${letter}" ${letter === 'A' ? 'required' : ''}>
                            <label for="correct_${letter.toLowerCase()}_${i}">Correct Answer</label>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
                questionsContainer.appendChild(questionDiv);
            }

            // Show the submit button
            document.getElementById('submitBtn').style.display = 'inline-block';
        }



        function hideQuestionForm() {
            document.getElementById('question-form-container').style.display = 'none';
            document.getElementById('addAptitudeBtn').style.display = 'inline-block';
        }

        function viewQuestions(bookId) {
            // Show loading state
            document.getElementById('questionsList').innerHTML = 'Loading...';
            document.getElementById('questionPopup').style.display = 'flex';

            // Fetch questions using AJAX with absolute path
            fetch('/Placement-/Admin/pages/get_questions.php?book_id=' + bookId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    let html = '';
                    data.forEach((question, index) => {
                        html += `
                            <div class="question-item">
                                <div class="question-text">Question ${index + 1}: ${question.question_text}</div>
                                <div class="option">A: ${question.option_a}</div>
                                <div class="option">B: ${question.option_b}</div>
                                <div class="option">C: ${question.option_c}</div>
                                <div class="option">D: ${question.option_d}</div>
                                <div class="correct-answer">Correct Answer: ${question.correct_answer}</div>
                            </div>
                        `;
                    });
                    
                    // Add Send button after questions
                    html += `
                        <div class="send-button-container">
                            <button onclick="sendQuestions(${bookId})" class="send-btn">
                                <i class="fas fa-paper-plane"></i> Send Questions
                            </button>
                        </div>
                    `;
                    
                    document.getElementById('questionsList').innerHTML = html || '<div class="alert alert-info">No questions found</div>';
                })
                .catch(error => {
                    document.getElementById('questionsList').innerHTML = 'Error loading questions: ' + error.message;
                    console.error('Error:', error);
                });
        }

        function sendQuestions(bookId) {
            if (confirm('Are you sure you want to send these questions?')) {
                alert('Questions sent successfully!');
                closePopup();
            }
        }

        function closePopup() {
            document.getElementById('questionPopup').style.display = 'none';
        }

        // Close popup when clicking outside
        window.onclick = function(event) {
            const popup = document.getElementById('questionPopup');
            if (event.target === popup) {
                closePopup();
            }
        }
    </script>
</body>

</html>