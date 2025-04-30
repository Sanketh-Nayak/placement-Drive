<?php
// Database connection
include 'db.php';

$email = $_SESSION['Email'];

$mergedNotifications = [];
$query = "SELECT stream FROM regd_studs WHERE Email = '$email'";
$result = mysqli_query($conn, $query);
if ($result) {
    $student = mysqli_fetch_assoc($result);

    if ($student) {
        $stream = $student['stream'];
        $announcement_query = "SELECT message, created_at as timestamp, 'announcement' as type FROM announcements WHERE stream = '$stream'";
        $announcement_result = mysqli_query($conn, $announcement_query);
        if ($announcement_result) {
            while ($row = mysqli_fetch_assoc($announcement_result)) {
                $mergedNotifications[] = $row;
            }
        }
    }
}
$Notification_query = "SELECT message, mag_date as timestamp, 'notification' as type FROM notification WHERE email = '$email'";
$Notification_result = mysqli_query($conn, $Notification_query);
if ($Notification_result) {
    while ($row = mysqli_fetch_assoc($Notification_result)) {
        $mergedNotifications[] = $row;
    }
}

usort($mergedNotifications, function ($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .notification-container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            text-align: center;
            margin: -25px -25px 25px -25px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #eaeaea;
            border-radius: 10px 10px 0 0;
        }

        .page-header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            display: inline-block;
        }

        .page-header h2:after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: #007bff;
            margin: 8px auto 0;
            border-radius: 2px;
        }

        .notification {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            transition: all 0.3s ease;
        }

        .notification:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .notification.unread {
            border-left: 4px solid #007bff;
            background: white;
        }

        .notification-content {
            flex: 1;
            padding-right: 15px;
            font-family: 'Calibri';
        }

        .notification p {
            margin: 0;
            color: #2c3e50;
            font-size: 16px;
            line-height: 1.5;
        }

        .time {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }

        .mark-read {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .mark-read:hover {
            background: rgba(0, 123, 255, 0.1);
            text-decoration: none;
        }

        /* Empty state */
        .no-notifications {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .notification-container {
                width: 95%;
                padding: 15px;
                margin: 10px auto;
            }

            .notification {
                padding: 12px 15px;
            }

            h2 {
                font-size: 1.2rem;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .notification {
                flex-direction: column;
                align-items: flex-start;
            }

            .notification-content {
                padding-right: 0;
                margin-bottom: 10px;
            }

            .mark-read {
                align-self: flex-end;
            }
        }
    </style>
</head>

<body>
    <div class="notification-container">
        <div class="page-header">
            <h2>Notifications</h2>
        </div>

        <?php
        if (count($mergedNotifications) > 0) {
            foreach ($mergedNotifications as $item) {
                $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $item['timestamp']);

                if (!$createdAt) {
                    $createdAt = new DateTime($item['timestamp']);
                }

                $createdAtFormatted = $createdAt->format('F j, Y, g:i a');

        ?>
                <div class="notification unread">
                    <div class="notification-content">
                        <p>
                            <strong>
                                <?= $item['type'] === 'announcement' ? '📢 Announcement:' : '🔔 Notification:' ?>
                            </strong><br>
                            <?= htmlspecialchars($item['message']) ?>
                        </p>
                        <div class="time"><?= htmlspecialchars($createdAtFormatted) ?></div>
                    </div>
                </div>

            <?php
            }
        } else {
            ?>
            <div class="notification">
                <div class="notification-content">
                    <p>No notifications available.</p>
                </div>
            </div>
        <?php
        }
        ?>




</body>

</html>