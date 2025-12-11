<?php
session_start();
include('db.php'); // Include the database connection file

// Check if the user is logged in and is a pet owner
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php"); // Redirect to login if not logged in or wrong role
    exit();
}

$username = $_SESSION['username'];

// Check if pet ID is provided in the URL
if (isset($_GET['id'])) {
    $pet_id = intval($_GET['id']);

    // Fetch pet details from the database
    $sql = "SELECT * FROM pets WHERE id = $pet_id AND owner_name = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $pet = mysqli_fetch_assoc($result);
    } else {
        echo "Pet not found or you do not have permission to edit it.";
        exit();
    }
} else {
    echo "Pet ID is missing.";
    exit();
}

// Handle form submission for updating pet details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_pet'])) {
    $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
    $pet_age = mysqli_real_escape_string($conn, $_POST['pet_age']);
    $vaccine_status = mysqli_real_escape_string($conn, $_POST['vaccine_status']);
    $illness = mysqli_real_escape_string($conn, $_POST['illness']);
    $pet_picture = $pet['pet_picture']; // Keep the old picture if no new picture is uploaded

    // Check if a new file is uploaded
    if (isset($_FILES['pet_picture']) && $_FILES['pet_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["pet_picture"]["name"]);

        if (move_uploaded_file($_FILES["pet_picture"]["tmp_name"], $target_file)) {
            $pet_picture = $target_file;
        } else {
            echo "<div class='alert alert-danger'>Error uploading file!</div>";
        }
    }

    // Update pet details using correct column names
    $sql_update = "UPDATE pets SET 
                   name = '$pet_name', 
                   age = '$pet_age', 
                   vaccine_status = '$vaccine_status', 
                   illness = '$illness', 
                   pet_picture = '$pet_picture' 
                   WHERE id = $pet_id AND owner_name = '$username'";

    if (mysqli_query($conn, $sql_update)) {
        header("Location: viewpets.php"); // Redirect back to pet list
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pet Details - Care4Purrt</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Pet Details</h2>

    <!-- Edit Pet Form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="pet_name">Pet Name:</label>
            <input type="text" name="pet_name" id="pet_name" class="form-control" 
                   value="<?php echo htmlspecialchars($pet['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="pet_age">Pet Age:</label>
            <input type="number" name="pet_age" id="pet_age" class="form-control" 
                   value="<?php echo htmlspecialchars($pet['age']); ?>" required>
        </div>

        <div class="form-group">
            <label for="vaccine_status">Vaccine Status:</label>
            <select name="vaccine_status" id="vaccine_status" class="form-control" required>
                <option value="vaccinated" <?php echo ($pet['vaccine_status'] == 'vaccinated') ? 'selected' : ''; ?>>Vaccinated</option>
                <option value="not vaccinated" <?php echo ($pet['vaccine_status'] == 'not vaccinated') ? 'selected' : ''; ?>>Not Vaccinated</option>
            </select>
        </div>

        <div class="form-group">
            <label for="illness">Illness:</label>
            <input type="text" name="illness" id="illness" class="form-control" 
                   value="<?php echo htmlspecialchars($pet['illness']); ?>">
        </div>

        <div class="form-group">
            <label for="pet_picture">Upload Pet Picture:</label>
            <input type="file" name="pet_picture" id="pet_picture" class="form-control-file">
            <?php if(!empty($pet['pet_picture'])) { ?>
                <img src="<?php echo $pet['pet_picture']; ?>" alt="Current Pet Picture" width="100">
            <?php } ?>
        </div>

        <button type="submit" name="update_pet" class="btn btn-primary">Update Pet</button>
    </form>
</div>
</body>
</html>
