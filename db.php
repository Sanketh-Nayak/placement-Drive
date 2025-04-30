<?php
$conn = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD'),
    getenv('DB_DATABASE')
);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
