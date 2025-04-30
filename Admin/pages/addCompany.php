<?php
include './db.php';

// Add or Update Company
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST["name"]);
    $location = $conn->real_escape_string($_POST["location"]);
    $role = $conn->real_escape_string($_POST["role"]);
    $companyType = $conn->real_escape_string(($_POST['company_type']));
    $deadline = $conn->real_escape_string($_POST["deadline"]);
    $companyUrl = $conn->real_escape_string($_POST["company_url"]);

    if (!empty($_POST["id"])) {
        $id = (int) $_POST["id"];
        $sql = "UPDATE companies SET name='$name', location='$location', role='$role', compnayType='$companyType', deadline='$deadline', company_url='$companyUrl' WHERE id=$id";
    } else {
        $sql = "INSERT INTO companies (name, location, role, compnayType, deadline, company_url) VALUES ('$name', '$location', '$role', '$companyType', '$deadline', '$companyUrl')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: home.php?page=addCompany");  // Redirect back to the company page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete Company
if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    $conn->query("DELETE FROM companies WHERE id=$id");
    header("Location: home.php?page=addCompany");  // Redirect back to the company page
    exit();
}

// Fetch Companies
$result = $conn->query("SELECT * FROM companies");

$editData = ["id" => "", "name" => "", "location" => "", "role" => "", "compnayType" => "", "deadline" => "", "company_url" => ""];

if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $editResult = $conn->query("SELECT * FROM companies WHERE id=$id");
    if ($editRow = $editResult->fetch_assoc()) {
        $editData = $editRow;
    }
}
?>
<main class="main-content">
<h2><?= $editData["id"] ? "Edit" : "Add"; ?> Company</h2>

<form method="POST" action="home.php?page=addCompany">
    <input type="hidden" name="id" value="<?= ($editData["id"]) ?>">
    
    <label for="name">Company Name:</label>
    <input type="text" id="name" name="name" placeholder="Company Name" value="<?= ($editData["name"]) ?>" required>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" placeholder="Location" value="<?= ($editData["location"]) ?>" required>

    <label for="role">Role:</label>
    <input type="text" id="role" name="role" placeholder="Role" value="<?= ($editData["role"]) ?>" required>
    
    <label for="company_type">Company type</label>
    <select id="company_type" name="company_type" style="width: 100%; padding:10px;margin: 5px 0;">
        <option value="">Select</option>
        <option name="IT" value="IT" <?= ($editData["compnayType"] == "IT") ? "selected" : "" ?>>IT</option>
        <option name="Non_IT" value="Non_IT" <?= ($editData["compnayType"] == "Non_IT") ? "selected" : "" ?>>Non IT</option>
        <option name="All" value="All" <?= ($editData["compnayType"] == "All") ? "selected" : "" ?>>All</option>
    </select>

    <label for="company_url">Company Website URL:</label>
    <input type="url" id="company_url" name="company_url" placeholder="https://example.com" value="<?= ($editData["company_url"]) ?>" required>

    <label for="deadline">Application Deadline:</label>
    <input type="date" id="deadline" name="deadline" value="<?= ($editData["deadline"]) ?>" required>

    <button type="submit" id="submit-button"><?= $editData["id"] ? "Update Company" : "Add Company" ?></button>
</form>
<div class="display">
<h2>Company List</h2>
<table>
    <tr>
        <th>Company Name</th>
        <th>Location</th>
        <th>Role</th>
        <th>Company Type</th>
        <th>Website</th>
        <th>Deadline</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?= ($row["name"]) ?></td>
            <td><?= ($row["location"]) ?></td>
            <td><?= ($row["role"]) ?></td>
            <td><?= ($row["compnayType"]) ?></td>
            <td><a href="<?= ($row["company_url"]) ?>" target="_blank">Visit Website</a></td>
            <td><?= ($row["deadline"]) ?></td>
            <td>
                <a class="edit-btn" href="home.php?page=addCompany&edit=<?= $row["id"] ?>">Edit</a><br><br>
                <a class="delete-btn" href="home.php?page=addCompany&delete=<?= $row["id"] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</div>
</main>
<?php $conn->close(); ?>
