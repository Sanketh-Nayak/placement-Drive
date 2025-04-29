<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>

        form {
            margin: auto;
            background: #f7f7f7;
        }

        .form-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 550px;
            margin: auto;
        }

        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            height: 250px;
        }

        .form-group button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-group button:active {
            background-color: #004085;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Inform Students</h2>
        <form method="POST" action="http://localhost/Placement-/Admin/backend/send_announcement.php">
            <div class="form-group">
                <label for="options">Select Stream</label>
                <select id="options" name="options">
                    <option value="BCOM">BCOM</option>
                    <option value="BBA">BBA</option>
                    <option value="BCA">BCA</option>
                    <option value="BSC">BSC</option>
                    <option value="BA">BA</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" placeholder="Enter your message here..." ></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

</body>
</html>
