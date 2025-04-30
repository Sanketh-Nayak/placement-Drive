<?php

// Create connection
include './db.php';

// Fetch records
$sql = "SELECT fullname, email, approval FROM reg_hist ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Requests</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        } */
        .Noti_container {
            /* width: 80%; */
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-right:5px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            color: #000;
        }
        th {
            background-color: #007bff;
            color: white;
        }

        form {
            background-color: none;
            box-shadow: none;
            padding: 5px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
        .btn {
            padding: 5px 0px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin: 2px;
        }
        .accept {
            background-color: #28a745;
            color: white;
        }
        .reject {
            background-color: #dc3545;
            color: white;
        }
        /* Bold for Name Column */
        .name-bold {
            font-weight: bold;
        }
        /* Status Styling */
        .status-accepted {
            color: green;
            font-weight: bold;
            text-transform: capitalize;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
            text-transform: capitalize;
        }
    </style>
</head>
<body>

<div class="Noti_container">
    <h2>Student Registration Requests</h2>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td class="name-bold"><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <?php if ($row['approval'] == "pending") { ?>
                            <form method="POST" action="http://localhost/Placement-/Admin/backend/handle-reg-approval.php" style="display: flex; gap: 10px;">
                                <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                <button type="submit" name="approval-action" value="accept" class="btn accept">Accept</button>
                                <button type="submit" name="approval-action" value="reject" class="btn reject">Reject</button>
                            </form>
                        <?php } else { ?>
                            <span class="<?php echo ($row['approval'] == 'accepted') ? 'status-accepted' : 'status-rejected'; ?>">
                                <?php echo ucfirst($row['approval']); ?>
                            </span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p class="no-data">No records found.</p>
    <?php } ?>

</div>

</body>
</html>

<?php
$conn->close();
?>
