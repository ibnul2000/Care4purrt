<?php
session_start();
include('db.php');

// Check if the user is logged in and is a pet owner
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch pets
$sql = "SELECT * FROM pets WHERE owner_name = '$username'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CareTailor: Nutrition Plan - Care4Purrt</title>

<!-- BOOTSTRAP -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- ICONS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* -----------------------------------
 GLOBAL DESIGN
----------------------------------- */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #8EC5FC, #E0C3FC);
    min-height: 100vh;
    transition: 0.4s;
}

/* DARK MODE */
body.dark {
    background: #121212;
    color: #e0e0e0;
}

.dark .container-custom {
    background: rgba(40,40,40,0.7);
    backdrop-filter: blur(10px);
    color: white;
}

.dark .card {
    background: rgba(30,30,30,0.8);
}

.dark .navbar {
    background: rgba(30,30,30,0.9);
}

/* -----------------------------------
 NAVBAR
----------------------------------- */

.navbar {
    background: linear-gradient(90deg, #7F00FF, #E100FF);
    padding: 15px;
    margin-bottom: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.navbar-brand {
    color: white !important;
    font-weight: 600;
    font-size: 1.4rem;
}

.toggle-dark {
    background: white;
    color: #7F00FF;
    border-radius: 20px;
    padding: 6px 14px;
    font-weight: bold;
    cursor: pointer;
    border: none;
    transition: 0.3s;
}

.toggle-dark:hover {
    transform: scale(1.1);
}

/* -----------------------------------
 MAIN CONTAINER
----------------------------------- */

.container-custom {
    background: rgba(255,255,255,0.65);
    backdrop-filter: blur(10px);
    padding: 35px;
    border-radius: 20px;
    width: 95%;
    max-width: 900px;
    margin: auto;
    animation: fade 0.8s;
}

@keyframes fade {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* -----------------------------------
 PET CARDS
----------------------------------- */

.card {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    transition: 0.35s ease;
}

.card:hover {
    transform: translateY(-7px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}

.card-header {
    background: linear-gradient(135deg, #7F00FF, #E100FF);
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
}

.pet-img {
    width: 100%;
    height: 230px;
    object-fit: cover;
    border-bottom: 2px solid #eee;
}

/* DOCTOR SECTION */
.doctor-box {
    background: #f0f8ff;
    border-left: 5px solid #7F00FF;
    padding: 12px 18px;
    border-radius: 12px;
}

.dark .doctor-box {
    background: rgba(80,80,80,0.6);
}

/* BUTTON */
.btn-modern {
    background: linear-gradient(135deg, #FF512F, #DD2476);
    border: none;
    color: white !important;
    padding: 10px 22px;
    border-radius: 28px;
    font-size: 1.08rem;
    font-weight: 600;
    transition: 0.3s;
    text-decoration: none;
}

.btn-modern:hover {
    transform: scale(1.08);
    box-shadow: 0px 4px 15px rgba(0,0,0,0.22);
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a class="navbar-brand"><i class="fas fa-paw"></i> CareTailor</a>
    <button class="toggle-dark" onclick="toggleDarkMode()">
        <i class="fas fa-moon"></i>
    </button>
</nav>

<div class="container-custom">
<h2 class="text-center mb-4">🐾 Nutrition & Health Plan</h2>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <div class="card mb-4">

        <!-- Pet Image -->
        <?php if (!empty($row['pet_picture'])) { ?>
            <img src="<?php echo $row['pet_picture']; ?>" class="pet-img" alt="Pet Picture">
        <?php } else { ?>
            <img src="default_pet.jpg" class="pet-img" alt="Default Pet">
        <?php } ?>

        <div class="card-header">
            <i class="fas fa-dog"></i> <?php echo $row['pet_name']; ?> — Age: <?php echo $row['pet_age']; ?>
        </div>

        <div class="card-body">

            <h5 class="text-primary font-weight-bold"><i class="fas fa-notes-medical"></i> Health Status</h5>
            <p><?php echo $row['health_status']; ?></p>

            <h5 class="text-primary font-weight-bold"><i class="fas fa-bone"></i> Nutrition & Diet Plan</h5>
            <p><?php echo $row['diet_plan']; ?></p>

            <h5 class="text-primary font-weight-bold"><i class="fas fa-heartbeat"></i> Other Recommendations</h5>
            <p><?php echo $row['other_recommendations']; ?></p>

            <hr>

            <!-- Doctor Section -->
            <div class="doctor-box mt-3">
                <h5 class="text-success font-weight-bold">
                    <i class="fas fa-user-md"></i> Doctor's Information
                </h5>
                <p><strong>Name:</strong> <?php echo $row['doctor_name']; ?></p>
                <p><strong>Specialty:</strong> <?php echo $row['doctor_specialty']; ?></p>
            </div>

            <a href="pet_owner_dashboard.php" class="btn-modern mt-3"><i class="fas fa-arrow-left"></i> Back</a>

        </div>
    </div>

<?php } ?>

</div>

<script>
// DARK MODE TOGGLE
function toggleDarkMode() {
    document.body.classList.toggle("dark");
}
</script>

</body>
</html>
