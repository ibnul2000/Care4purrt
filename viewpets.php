<?php
session_start();
include('db.php'); 

// Check login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Add Pet
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_pet'])) {
    $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
    $pet_age = mysqli_real_escape_string($conn, $_POST['pet_age']);
    $vaccine_status = mysqli_real_escape_string($conn, $_POST['vaccine_status']);
    $illness = mysqli_real_escape_string($conn, $_POST['illness']);
    $pet_picture = "";

    if (isset($_FILES['pet_picture']) && $_FILES['pet_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["pet_picture"]["name"]);
        if (move_uploaded_file($_FILES["pet_picture"]["tmp_name"], $target_file)) {
            $pet_picture = $target_file;
        }
    }

    $sql = "INSERT INTO pets (name, age, owner_name, vaccine_status, illness, pet_picture) 
            VALUES ('$pet_name', '$pet_age', '$username', '$vaccine_status', '$illness', '$pet_picture')";
    mysqli_query($conn, $sql);
}

// Delete pet
if (isset($_GET['delete'])) {
    $pet_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM pets WHERE id = $pet_id");
}

// Fetch pets
$result = mysqli_query($conn, "SELECT * FROM pets WHERE owner_name = '$username'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Pets - Care4Purrt</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(120deg,#fdfbfb,#ebedee);
    font-family: 'Poppins', sans-serif;
}

/* Header */
.page-header h2 {
    font-size: 2.8rem;
    font-weight: 800;
    background: linear-gradient(90deg,#ff7e5f,#feb47b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Add Pet Card */
.add-pet-card {
    background: #fff;
    padding: 30px;
    border-radius: 25px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    margin-bottom: 40px;
}

/* Pet Cards Grid – 3 cards per row */
.pet-card {
    background:#fff;
    border-radius:20px;
    box-shadow:0 15px 30px rgba(0,0,0,0.1);
    height:100%;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    transition:0.3s;
}
.pet-card:hover {
    transform: translateY(-5px);
    box-shadow:0 20px 40px rgba(0,0,0,0.2);
}

.pet-card img {
    border-top-left-radius:20px;
    border-top-right-radius:20px;
    width:100%;
    height:200px;
    object-fit:cover;
}

/* Buttons */
.btn-edit {
    background: linear-gradient(45deg,#43cea2,#185a9d);
    color:#fff;
    border-radius:25px;
}

.btn-delete {
    background: linear-gradient(45deg,#ff416c,#ff4b2b);
    color:#fff;
    border-radius:25px;
}

.btn-home {
    background: linear-gradient(45deg,#ff7e5f,#feb47b);
    color:#fff;
    border-radius:30px;
    padding:12px 30px;
}
</style>

</head>
<body>

<div class="container mt-5">

    <div class="page-header text-center mb-5">
        <h2>Manage Your Pets</h2>
        <p>Add, edit, or delete pets in a modern colorful interface.</p>
    </div>

    <!-- Add Pet Card -->
    <div class="add-pet-card">
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="pet_name" placeholder="Pet Name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <input type="number" name="pet_age" placeholder="Pet Age" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <select name="vaccine_status" class="form-control" required>
                        <option value="">Vaccine Status</option>
                        <option value="vaccinated">Vaccinated</option>
                        <option value="not vaccinated">Not Vaccinated</option>
                    </select>
                </div>
                <div class="col-12">
                    <input type="text" name="illness" placeholder="Illness (optional)" class="form-control">
                </div>
                <div class="col-12">
                    <input type="file" name="pet_picture" class="form-control">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" name="add_pet" class="btn btn-add btn-warning px-4">
                        Add Pet <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Pet Cards Grid -->
    <div class="row g-4">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 d-flex">
                    <div class="card pet-card w-100">

                        <img src="<?php echo !empty($row['pet_picture']) ? $row['pet_picture'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>">

                        <div class="card-body text-center">
                            <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p><strong>Age:</strong> <?php echo $row['age']; ?> years</p>
                            <p><strong>Vaccine:</strong> <?php echo $row['vaccine_status']; ?></p>
                            <p><strong>Illness:</strong> <?php echo $row['illness']; ?></p>

                            <a href="edit_pet.php?id=<?php echo $row['id']; ?>" class="btn btn-edit btn-sm me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <a href="viewpets.php?delete=<?php echo $row['id']; ?>" 
                               class="btn btn-delete btn-sm"
                               onclick="return confirm('Are you sure you want to delete this pet?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">No pets added yet.</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Home Button -->
    <div class="text-center mt-5">
        <a href="pet_owner_dashboard.php" class="btn btn-home btn-lg">
            <i class="fas fa-home me-2"></i> Go Back
        </a>
    </div>

</div>

</body>
</html>
