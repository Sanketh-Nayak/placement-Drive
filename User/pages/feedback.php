<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            width: 100%;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 650px;
            margin: auto;
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-2px);
        }

        .form-container h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            font-size: 15px;
            font-weight: 500;
            color: #2c3e50;
            display: block;
            margin-bottom: 8px;
        }

        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background-color: #ffffff;
            color: #2c3e50;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 200px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .form-group textarea::placeholder {
            color: #adb5bd;
        }

        .flex {
            display: flex;
            justify-content: center;
            margin-top: 32px;
        }

        .form-group button {
            background-color: #4a90e2;
            color: white;
            padding: 14px 32px;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: auto;
            min-width: 160px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.15);
        }

        .form-group button:hover {
            background-color: #357abd;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(74, 144, 226, 0.2);
        }

        .form-group button:active {
            background-color: #2b62a0;
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(74, 144, 226, 0.15);
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 24px;
            }

            .form-container h2 {
                font-size: 24px;
            }

            .form-group button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Provide Feedback</h2>
        <form method="POST" action="http://localhost/Placement-/User/backend/send_feedback.php">
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" placeholder="Enter your message here..." ></textarea>
            </div>
            <div class="form-group flex">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

</body>
</html>
