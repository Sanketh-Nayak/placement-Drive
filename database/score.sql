CREATE TABLE IF NOT EXISTS quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    email VARCHAR(255), 
    book_title VARCHAR(255), 
    score INT, 
    total_questions INT, 
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP);