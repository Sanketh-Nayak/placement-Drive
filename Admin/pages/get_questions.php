<?php
header('Content-Type: application/json');

// Database connection
$conn = @mysqli_connect('localhost', 'root', '', 'admin_db');
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Get book_id from request
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

if ($book_id <= 0) {
    die(json_encode(['error' => 'Invalid book ID']));
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT question_text, option_a, option_b, option_c, option_d, correct_answer 
                       FROM questions 
                       WHERE book_id = ?
                       ORDER BY question_id ASC");

if (!$stmt) {
    die(json_encode(['error' => 'Query preparation failed']));
}

$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = [
        'question_text' => htmlspecialchars($row['question_text']),
        'option_a' => htmlspecialchars($row['option_a']),
        'option_b' => htmlspecialchars($row['option_b']),
        'option_c' => htmlspecialchars($row['option_c']),
        'option_d' => htmlspecialchars($row['option_d']),
        'correct_answer' => htmlspecialchars($row['correct_answer'])
    ];
}

$stmt->close();
$conn->close();

echo json_encode($questions);
?> 