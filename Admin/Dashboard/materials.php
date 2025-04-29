<?php
include 'header.php';
include '../config.php';

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file = $_FILES['file'];
    $file_type = $_POST['file_type'];
    
    $target_dir = "../uploads/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
    // Validate file type
    if ($file_type === 'pdf' && $file_extension !== 'pdf') {
        $error = "Only PDF files are allowed for PDF type.";
    } elseif ($file_type === 'image' && !in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $error = "Only JPG, JPEG, PNG & GIF files are allowed for Image type.";
    } else {
        // Generate unique filename
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO materials (title, description, file_path, file_type, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $title, $description, $new_filename, $file_type);
            
            if ($stmt->execute()) {
                $success = "File uploaded successfully.";
            } else {
                $error = "Error saving to database.";
            }
        } else {
            $error = "Error uploading file.";
        }
    }
}

// Fetch materials
$sql = "SELECT * FROM materials ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="content">
    <div class="content-header">
        <h2>Study Materials</h2>
        <button class="add-btn" onclick="showAddForm()">Add New Material</button>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Add Material Form -->
    <div id="addMaterialForm" class="form-container" style="display: none;">
        <h3>Add New Material</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label>File Type</label>
                <select name="file_type" required onchange="updateFileInput()">
                    <option value="">Select Type</option>
                    <option value="pdf">PDF</option>
                    <option value="image">Image</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>File</label>
                <input type="file" name="file" id="fileInput" required>
                <small id="fileHelp" class="form-text text-muted"></small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-secondary" onclick="hideAddForm()">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Materials List -->
    <div class="materials-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="material-card">
                    <div class="material-icon">
                        <?php if ($row['file_type'] === 'pdf'): ?>
                            <i class="fas fa-file-pdf"></i>
                        <?php else: ?>
                            <i class="fas fa-file-image"></i>
                        <?php endif; ?>
                    </div>
                    <div class="material-info">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="material-meta">
                            <span>Added: <?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                            <a href="../uploads/<?php echo $row['file_path']; ?>" target="_blank" class="view-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-data">No materials available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function showAddForm() {
    document.getElementById('addMaterialForm').style.display = 'block';
}

function hideAddForm() {
    document.getElementById('addMaterialForm').style.display = 'none';
}

function updateFileInput() {
    const fileType = document.querySelector('select[name="file_type"]').value;
    const fileInput = document.getElementById('fileInput');
    const fileHelp = document.getElementById('fileHelp');
    
    if (fileType === 'pdf') {
        fileInput.accept = '.pdf';
        fileHelp.textContent = 'Only PDF files are allowed';
    } else if (fileType === 'image') {
        fileInput.accept = 'image/*';
        fileHelp.textContent = 'Only JPG, JPEG, PNG & GIF files are allowed';
    } else {
        fileInput.accept = '';
        fileHelp.textContent = '';
    }
}
</script>

<style>
.content {
    padding: 20px;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.add-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.form-container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input[type="text"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.materials-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.material-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 15px;
    display: flex;
    gap: 15px;
}

.material-icon {
    font-size: 24px;
    color: #007bff;
}

.material-info {
    flex: 1;
}

.material-info h3 {
    margin: 0 0 10px 0;
    font-size: 16px;
}

.material-info p {
    margin: 0 0 10px 0;
    color: #666;
    font-size: 14px;
}

.material-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: #888;
}

.view-btn {
    color: #007bff;
    text-decoration: none;
}

.view-btn:hover {
    text-decoration: underline;
}

.no-data {
    text-align: center;
    color: #666;
    grid-column: 1 / -1;
}

.alert {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
</style>

<?php include 'footer.php'; ?> 