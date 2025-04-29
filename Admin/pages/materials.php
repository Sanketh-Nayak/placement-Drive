<?php
// Direct database connection
$conn = mysqli_connect("localhost", "root", "", "admin_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    $response = array();
    
    try {
        // First get the file path
        $sql = "SELECT file_path FROM materials WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Delete the file
            $file_path = "../../uploads/" . $row['file_path'];
            if (file_exists($file_path)) {
                if (!unlink($file_path)) {
                    throw new Exception("Error deleting file from server");
                }
            }
            
            // Delete from database
            $delete_sql = "DELETE FROM materials WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            if (!$delete_stmt) {
                throw new Exception("Error preparing delete statement: " . $conn->error);
            }
            
            $delete_stmt->bind_param("i", $id);
            if ($delete_stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Material deleted successfully.";
            } else {
                throw new Exception("Error deleting from database: " . $delete_stmt->error);
            }
            $delete_stmt->close();
        } else {
            throw new Exception("Material not found");
        }
        $stmt->close();
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $title = $_POST['title'];
    $file = $_FILES['file'];
    
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
    // Generate unique filename
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        $sql = "INSERT INTO materials (title, file_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            $_SESSION['error'] = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $title, $new_filename);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Material added successfully!";
            } else {
                $_SESSION['error'] = "Error saving to database: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $_SESSION['error'] = "Error uploading file.";
    }
    
    // Redirect after POST to prevent form resubmission
    header("Location: materials.php");
    exit();
}

// Fetch materials
$sql = "SELECT * FROM materials ORDER BY id DESC";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    $_SESSION['error'] = "Error fetching materials: " . $conn->error;
}

// Get messages from session and clear them
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['error'], $_SESSION['success']);
?>
<main class="main-content">
    <div class="content">
        <div class="content-header">
            <h2>Study Materials</h2>
            <button class="add-btn" onclick="showAddForm()">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Add Material Form -->
        <div id="addMaterialForm" class="form-container" style="display: none;">
            <h3>Add New Material</h3>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>File</label>
                    <input type="file" name="file" id="fileInput" required>
                    <small id="fileHelp" class="form-text text-muted">Supported formats: PDF, JPG, JPEG, PNG, GIF</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Materials List -->
        <div class="materials-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="material-card" data-material-id="<?php echo $row['id']; ?>">
                        <div class="material-icon">
                            <?php 
                            $extension = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));
                            if ($extension === 'pdf'): 
                            ?>
                                <i class="fas fa-file-pdf"></i>
                            <?php else: ?>
                                <i class="fas fa-file-image"></i>
                            <?php endif; ?>
                        </div>
                        <div class="material-info">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <div class="material-meta">
                                <a href="../../uploads/<?php echo $row['file_path']; ?>" target="_blank" class="view-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="delete-btn" onclick="deleteMaterial(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-data">No materials available.</p>
            <?php endif; ?>
        </div>
    </div>
</main>
<style>
.main-content {
    padding: 20px;
    background: #f8f9fa;
    min-height: calc(100vh - 60px);
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.content-header h2 {
    margin: 0;
    font-size: 20px;
    color: #2c3e50;
    font-weight: 500;
}

.add-btn {
    background: #3498db;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    transition: background 0.3s;
    width: 80px;
    justify-content: center;
}

.add-btn:hover {
    background: #2980b9;
}

.materials-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
}

.material-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    gap: 15px;
    transition: transform 0.3s;
}

.material-card:hover {
    transform: translateY(-5px);
}

.material-icon {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.material-icon i {
    color: #3498db;
}

.material-info {
    flex: 1;
}

.material-info h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
    font-size: 16px;
}

.material-meta {
    display: flex;
    gap: 10px;
}

.view-btn, .delete-btn {
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    transition: all 0.3s;
}

.view-btn {
    background: #3498db;
    color: white;
}

.view-btn:hover {
    background: #2980b9;
}

.delete-btn {
    background: #e74c3c;
    color: white;
    padding:6px;
    border: none;
    padding: 6px 12px;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    width:auto;
    align-items: center;
    /* gap: 5px; */
    font-size: 14px;
    transition: all 0.3s;
}

.delete-btn:hover {
    background: #c0392b;
}

.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #2c3e50;
}

.form-group input[type="text"],
.form-group input[type="file"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.form-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    animation: slideIn 0.3s ease-out;
}

.alert i {
    font-size: 18px;
}

.alert-danger {
    background: #fff5f5;
    color: #e53e3e;
    border-left: 4px solid #e53e3e;
}

.alert-success {
    background: #f0fff4;
    color: #2f855a;
    border-left: 4px solid #2f855a;
}

@keyframes slideIn {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.no-data {
    text-align: center;
    color: #7f8c8d;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<script>
function showAddForm() {
    document.getElementById('addMaterialForm').style.display = 'block';
}

function hideAddForm() {
    document.getElementById('addMaterialForm').style.display = 'none';
}

// Auto-hide alerts after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 3000);
    });
});

// Show alert function
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
        ${message}
    `;
    document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.materials-list'));
    
    // Auto-hide the alert
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            alertDiv.remove();
        }, 300);
    }, 3000);
}

// Update delete function to use the correct path
function deleteMaterial(id) {
    if (confirm('Are you sure you want to delete this material?')) {
        fetch(`/Placement-/Admin/pages/materials.php?delete=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the material card from the DOM
                    const materialCard = document.querySelector(`[data-material-id="${id}"]`);
                    if (materialCard) {
                        materialCard.remove();
                    }
                    showAlert(data.message, 'success');
                    
                    // If no materials left, show the no data message
                    const materialsList = document.querySelector('.materials-list');
                    if (materialsList.children.length === 0) {
                        materialsList.innerHTML = '<p class="no-data">No materials available.</p>';
                    }
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting material', 'danger');
            });
    }
}

// Add form submission handler
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/Placement-/Admin/pages/materials.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        // Refresh the materials list
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newMaterialsList = doc.querySelector('.materials-list');
        document.querySelector('.materials-list').innerHTML = newMaterialsList.innerHTML;
        
        // Show success message
        showAlert('Material added successfully!', 'success');
        
        // Clear form and hide it
        document.getElementById('uploadForm').reset();
        hideAddForm();
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error adding material', 'danger');
    });
});
</script>