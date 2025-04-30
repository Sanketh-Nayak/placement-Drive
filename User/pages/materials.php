<?php

include 'db.php';

// Fetch materials
$sql = "SELECT * FROM materials ORDER BY title";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Error fetching materials: " . $conn->error);
}
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .materials-section {
            padding: 20px;
            background: rgb(243, 232, 223);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .materials-header {
            background: rgb(243, 232, 223);
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .materials-header h2 {
            margin: 0;
            color: #333;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .material-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background: rgb(243, 232, 223);
            border-radius: 6px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .material-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .material-title {
            font-size: 1rem;
            color: #333;
            margin: 0;
            flex-grow: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .action-buttons a {
            color: #333;
            font-size: 1.2rem;
            text-decoration: none;
            padding: 5px;
            cursor: pointer;
            display: inline-block;
            width: 30px;
            height: 30px;
            text-align: center;
            line-height: 30px;
        }
        .fa:hover{
            color: blue;
        }

        .no-data {
            text-align: center;
            color: #666;
            padding: 20px;
        }
    </style>
</head>
<div class="materials-section">
    <div class="materials-header">
        <h2>Study Materials</h2>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="material-item">
                <h3 class="material-title">
                    <i class="fas fa-file-pdf"></i>
                    <?php echo htmlspecialchars($row['title']); ?>
                </h3>
                <div class="action-buttons">
                    <a href="../../Admin/uploads/<?php echo $row['file_path']; ?>" target="_blank" title="View PDF">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="../../Admin/uploads/<?php echo $row['file_path']; ?>" download title="Download PDF">
                        <i class="fa fa-download"></i>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-data">No materials available.</p>
    <?php endif; ?>
</div>