<?php
session_start();
if (isset($_POST['confirm_logout'])) {
    session_destroy();
    header("Location: ../Login/student.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <style>
        .modal {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 500px;
            margin: 15% 33%;
            text-align: center;
        }

        .modal h2 {
            font-size: 18px;
        }

        .modal form {
            margin-top: 20px;
        }

        .modal input[type="submit"] {
            padding: 10px 20px;
            margin: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #ccc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal input[type="submit"]:hover,
        .modal a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="modal">
        <div class="modal-content">
            <h2>Are you sure you want to log out?</h2>
            <form method="POST">
                <input type="submit" name="confirm_logout" value="Yes, Log me out">
            </form>
            <a href="../home.php">Cancel</a>
        </div>
    </div>

</body>
</html>
