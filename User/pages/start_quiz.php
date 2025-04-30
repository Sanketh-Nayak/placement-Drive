<?php
session_start();
include './db.php';


$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
$book_title = '';
if ($book_id > 0) {
    $book_sql = "SELECT title FROM books WHERE book_id = $book_id";
    $book_result = mysqli_query($conn, $book_sql);
    if ($book_row = $book_result->fetch_assoc()) {
        $book_title = $book_row['title'];
    }
}


$questions = [];
if ($book_id > 0) {
    $sql = "SELECT * FROM questions WHERE book_id = $book_id";
    $result = mysqli_query($conn,$sql);
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = intval($_POST['score']);
    $email = $_SESSION['Email'];
    $total_questions = count($questions);
    
    $insert_sql = "INSERT INTO quiz_results (email, book_title, score, total_questions) 
                   VALUES ('$email', '$book_title', $score, $total_questions)";
    mysqli_query($conn, $insert_sql);
    
    header('Location: ../home.php?page=ActivityList');
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start Quiz</title>
    <style>
        .quiz-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .timer {
            font-size: 18px;
            text-align: right;
            margin-bottom: 20px;
            color: #ff0000;
            font-weight: bold;
        }

        .question {
            margin-bottom: 20px;
        }

        .options label {
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .options input {
            margin-right: 10px;
        }

        .navigation {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 10px 18px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
        }

        button:hover {
            background-color: #45a049;
        }

        .result {
            font-size: 20px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="quiz-container">
    <div class="timer" id="timer">Time Remaining: <span id="time">03:00</span></div>

    <?php if (count($questions) > 0): ?>
        <form id="quizForm">
            <?php foreach ($questions as $index => $q): ?>
                <div class="question-block" id="q<?= $index ?>">
                    <div class="question"><strong>Q<?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['question_text']) ?></div>
                    <div class="options">
                        <label><input type="radio" name="answer<?= $index ?>" value="A"> <?= htmlspecialchars($q['option_a']) ?></label>
                        <label><input type="radio" name="answer<?= $index ?>" value="B"> <?= htmlspecialchars($q['option_b']) ?></label>
                        <label><input type="radio" name="answer<?= $index ?>" value="C"> <?= htmlspecialchars($q['option_c']) ?></label>
                        <label><input type="radio" name="answer<?= $index ?>" value="D"> <?= htmlspecialchars($q['option_d']) ?></label>
                    </div>
                    <hr>
                </div>
            <?php endforeach; ?>
            <div style="text-align: center; margin-top: 20px;">
                <button type="button" onclick="submitQuiz()">Submit Quiz</button>
            </div>
        </form>
        <div class="result" id="result"></div>
        <form id="scoreForm" method="POST" style="display: none;">
            <input type="hidden" name="score" id="scoreInput">
        </form>
    <?php else: ?>
        <p>No questions found for this book.</p>
    <?php endif; ?>
</div>

<script>
let correctAnswers = <?= json_encode(array_column($questions, 'correct_answer')) ?>;
let totalQuestions = <?= count($questions) ?>;

function submitQuiz() {
    let score = 0;
    for (let i = 0; i < totalQuestions; i++) {
        let selected = document.querySelector('input[name="answer' + i + '"]:checked');
        if (selected && selected.value === correctAnswers[i]) {
            score++;
        }
    }
    document.getElementById('quizForm').style.display = 'none';
    document.getElementById('result').innerHTML = `You scored <strong>${score}</strong> out of <strong>${totalQuestions}</strong>`;
    document.getElementById('scoreInput').value = score;
    
    setTimeout(() => {
        document.getElementById('scoreForm').submit();
    }, 1500);
}

let timeLeft =  2 * 60;
let timerDisplay = document.getElementById('time');

function updateTimer() {
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        submitQuiz();
        return;
    }

    let minutes = Math.floor(timeLeft / 60);
    let seconds = timeLeft % 60;
    timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    sessionStorage.setItem('timeLeft', timeLeft);
    timeLeft--;
}

let timerInterval = setInterval(updateTimer, 1000);
updateTimer();
</script>

</body>
</html>
